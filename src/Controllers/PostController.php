<?php

declare(strict_types=1);

namespace TheFileHub\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use TheFileHub\Models\Post;
use TheFileHub\Models\User;
use TheFileHub\Services\UserService;
use \TheFileHub\Core\Database;

class PostController extends BaseController
{
    /**
     * Show all posts
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function all(Request $request, Response $response): Response
    {
        if (!UserService::isConnected()) {
            return UserService::unAuthorized($response, $request, $this->view);
        }

        $posts = Post::All();
        foreach ($posts as $post) {
            $post->fileExt = self::fromPath($post->fileLink);
        }

        return $this->view->render($response, 'posts/all.php', [
            'title' => 'The TheFileHub | All Posts',
            'posts' => $posts
        ]);
    }

    /**
     * Show new post form
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function new(Request $request, Response $response): Response
    {
        if (!UserService::isConnected()) {
            return UserService::unAuthorized($response, $request, $this->view);
        }

        return $this->view->render($response, 'posts/new.php');
    }

    /**
     * Handle new post submission
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function submit(Request $request, Response $response): Response
    {
        if (!UserService::isConnected()) {
            return UserService::unAuthorized($response, $request, $this->view);
        }

        $user = UserService::current();
        $data = $request->getParsedBody();
        $uploadedFile = $request->getUploadedFiles()['file'] ?? null;
        $description = $data['desc'] ?? "no description";
        $name = $data['name'] ?? "no title";

        if (!$uploadedFile || $uploadedFile->getError() !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'File upload failed.' . ($uploadedFile ? $uploadedFile->getError() : 'No file uploaded.');
            return $response->withHeader('Location', '/post/new')->withStatus(302);
        }

        $uploadDir = __DIR__ . '/../../public/uploads/posts';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $filename = 'post_' . date('Y-m-d--H-i-s') . '_' . $_SESSION['user']->getUsername() . '.' . $extension;

        $uploadedFile->moveTo($uploadDir . '/' . $filename);

        $post = new Post();
        $post->setCreator($user->getIdUser());
        $post->setFileLink('/uploads/posts/' . $filename);
        $post->name = $name;
        $post->setDescription($description);
        $post->setLikes(0);
        $post->setDislikes(0);

        if (!$post->insert()) {
            $_SESSION['error'] = 'Impossible de créer le post.';
            return $response->withHeader('Location', '/posts/new')->withStatus(302);
        }

        $_SESSION['success'] = 'Post créé avec succès';

        return $response
            ->withHeader('Location', '/posts')
            ->withStatus(302);
    }

    /**
     * Show single post
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function single(Request $request, Response $response): Response
    {
        if (!UserService::isConnected()) {
            return UserService::unAuthorized($response, $request, $this->view);
        }

        $idPost = (int) explode('/', $request->getUri()->getPath())[3];

        $post = Post::findById($idPost);

        if (!$post) {
            $_SESSION['error'] = "Post not found.";
            return $response->withHeader('Location', '/posts')->withStatus(302);
        }

        $user = UserService::current();
        $isCreator = $user->getIdUser() === $post->getCreator()->idUser;

        $fullPath = __DIR__ . '/../../public' . $post->getFileLink();

        $fileSize = file_exists($fullPath)
            ? round(filesize($fullPath) / 1024, 2) . ' KB'
            : 'N/A';

        $fileExt = pathinfo($post->getFileLink(), PATHINFO_EXTENSION);

        return $this->view->render($response, 'posts/single.php', [
            'title' => 'The TheFileHub | Single Post',
            'post' => $post,
            'isCreator' => $isCreator,
            'fileSize' => $fileSize,
            'fileExt' => $fileExt
        ]);
    }

    /**
     * Show post update form
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function singleToUpdate(Request $request, Response $response): Response
    {
        if (!UserService::isConnected()) {
            return UserService::unAuthorized($response, $request, $this->view);
        }

        $idPost = (int) explode('/', $request->getUri()->getPath())[3];

        $post = Post::findById($idPost);

        return $this->view->render($response, 'posts/update.php', [
            'post' => $post
        ]);
    }

    /**
     * Handle post update submission
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function singleUpdate(Request $request, Response $response): Response
    {
        if (!UserService::isConnected()) {
            return UserService::unAuthorized($response, $request, $this->view);
        }

        $data = $request->getParsedBody();

        $idPost = (int) $data['idPost'];
        $newName = $data['name'] ?? null;
        $newDescription = $data['desc'] ?? null;

        $post = Post::findById($idPost);

        if ($newName == null || $newDescription == null) {
            $_SESSION['error'] = "Name and description cannot be empty.";
            return $response->withHeader('Location', "/post/single/{$idPost}/update")->withStatus(302);
        }

        if ($post) {
            $post->setName($newName);
            $post->setDescription($newDescription);
            $post->update();
        }

        return $this->view->render($response, 'posts/single.php', [
            'post' => $post
        ]);
    }

    /**
     * Handle post delete submission
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function singleDelete(Request $request, Response $response): Response
    {
        if (!UserService::isConnected()) {
            return UserService::unAuthorized($response, $request, $this->view);
        }

        $idPost = (int) $request->getParsedBody()['idPost'];
        $fromMod = (int) $request->getParsedBody()['fromMod'];

        $post = Post::findById($idPost);

        if (!$post) {
            $_SESSION['error'] = "Post not found.";
            return $response->withHeader('Location', '/posts')->withStatus(302);
        }

        $post->delete();

        $_SESSION['success'] = "Post deleted successfully.";

        if ($fromMod) {
            return $response->withHeader('Location', '/mod')->withStatus(302);
        }
        return $response->withHeader('Location', '/posts')->withStatus(302);
    }

    /**
     * Manage post likes and dislikes
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function gestionLikes(Request $request, Response $response, array $args): Response
    {
        if (!UserService::isConnected()) {
            return UserService::unAuthorized($response, $request, $this->view);
        }

        $idPost = (int) $args['id'];
        $type = $args['type'];
        $user = UserService::current();
        $userId = $user->getIdUser();


        $post = Post::findById($idPost);

        $post->modifyLikes($response, $type);

        if (!$post) {
            $_SESSION['error'] = "Post not found.";
            return $response->withHeader('Location', '/posts')->withStatus(302);
        }

        $creator = $post->getCreator();

        if ($type === 'like') {
            $post->setLikes($post->getLikes() + 1);
            $post->action = 1;
            $creator->setXp_Add(20);
        } elseif ($type === 'dislike') {
            $post->setDislikes($post->getDislikes() + 1);
            $post->action = 0;
            $creator->setXp_Add(-20);
        }

        $post->update();
        $creator->update();

        return $response
            ->withHeader('Location', "/post/single/$idPost")
            ->withStatus(302);
    }

    /**
     * Returns a file representation based on the file extension extracted from the given path.
     *
     * This method extracts the extension from the provided file path and attempts to retrieve
     * a corresponding file representation using the `fromExt` method. If no representation is found,
     * it returns the original path.
     *
     * @param string $path The file path from which to extract the extension.
     * @return string The file representation based on the extension, or the original path if not found.
     */
    public static function fromPath(string $path): string
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        $file = self::fromExt($ext);

        if (empty($file)) {
            return $path;
        }

        return $file;
    }

    /**
     * Returns the asset path for the icon corresponding to a given file extension.
     *
     * Maps the provided file extension to a file type using a predefined map,
     * then retrieves the appropriate icon filename using getFileIcon().
     * If the icon is 'image.png', returns an empty string.
     * Otherwise, returns the path to the icon in the assets/defaults directory.
     *
     * @param string $ext The file extension to map (e.g., 'jpg', 'mp3').
     * @return string The asset path for the icon, or an empty string if the icon is 'image.png'.
     */
    public static function fromExt(string $ext): string
    {
        $extTypeMap = [
            'jpg' => 'image',
            'jpeg' => 'image',
            'png' => 'image',
            'gif' => 'image',
            'mp4' => 'video',
            'mp3' => 'audio',
            'txt' => 'text',
            'exe' => 'executable windows'
        ];

        $icon = self::getFileIcon($extTypeMap[$ext] ?? 'file');

        if ($icon === 'image.png') {
            return "";
        }

        return "assets/defaults/$icon";
    }

    /**
     * Returns the filename of the icon image corresponding to a given MIME type.
     *
     * This method maps common MIME type groups (e.g., 'image', 'video', 'audio', 'text', 'executable windows')
     * to specific icon filenames. If the MIME type is not directly mapped, it attempts to use the group part
     * of the MIME type (the substring before the '/') to find a matching icon. If no match is found, a default
     * icon filename is returned.
     *
     * @param string $mime The MIME type of the file (e.g., 'image/png', 'video/mp4').
     * @return string The filename of the corresponding icon image.
     */
    public static function getFileIcon(string $mime): string
    {
        $iconMap = [
            'image' => 'image.png',
            'video' => 'video.png',
            'audio' => 'audio.png',
            'text' => 'text.png',
            'executable windows' => 'exe.png',
            'file' => 'untracked.png'
        ];


        if (isset($iconMap[$mime])) {
            return $iconMap[$mime];
        }

        $group = explode('/', $mime)[0];
        return $iconMap[$group] ?? 'file.png';
    }
}