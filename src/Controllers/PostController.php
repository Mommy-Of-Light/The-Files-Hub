<?php

declare(strict_types=1);

namespace TheFileHub\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use TheFileHub\Models\Post;
use TheFileHub\Models\User;
use TheFileHub\Services\UserService;

class PostController extends BaseController
{
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

    public function new(Request $request, Response $response): Response
    {
        if (!UserService::isConnected()) {
            return UserService::unAuthorized($response, $request, $this->view);
        }

        return $this->view->render($response, 'posts/new.php');
    }

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
            $_SESSION['error'] = 'File upload failed.';
            return $response->withHeader('Location', '/posts/new')->withStatus(302);
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

    public function single(Request $request, Response $response): Response
    {
        if (!UserService::isConnected()) {
            return UserService::unAuthorized($response, $request, $this->view);
        }

        // id sent by url get
        $idPost = (int)explode('/', $request->getUri()->getPath())[3];

        return $this->view->render($response, 'posts/single.php', [
            'title' => 'The TheFileHub | Single Post',
            'post' => Post::findById($idPost)
        ]);
    }
    public function singleLike(Request $request, Response $response): Response
    {
        if (!UserService::isConnected()) {
            return UserService::unAuthorized($response, $request, $this->view);
        }

        return $this->view->render($response, 'posts/single.php');
    }

    public function singleUpdate(Request $request, Response $response): Response
    {
        if (!UserService::isConnected()) {
            return UserService::unAuthorized($response, $request, $this->view);
        }

        return $this->view->render($response, 'posts/single.php');
    }

    public function singleDelete(Request $request, Response $response): Response
    {
        if (!UserService::isConnected()) {
            return UserService::unAuthorized($response, $request, $this->view);
        }

        return $this->view->render($response, 'posts/single.php');
    }
    private static function fromPath(string $path): string {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        return self::fromExt($ext);
    }

    private static function fromExt(string $ext): string
    {
        $extTypeMap = [
            'jpg' => 'image',
            'jpeg' => 'image',
            'png' => 'image',
            'gif' => 'image',
            'mp4' => 'video',
            'mp3' => 'audio',
            'txt' => 'text',
        ];

        $icon = self::getFileIcon($extTypeMap[$ext] ?? 'file');

        return "assets/defaults/$icon";
    }    
    
    private static function getFileIcon(string $mime): string
    {
        $iconMap = [
            'image' => 'image.png',
            'video' => 'video.png',
            'audio' => 'audio.png',
            'text' => 'text.png',

            'application/pdf' => 'pdf.png',
            'application/zip' => 'archive.png',
            'application/x-rar' => 'archive.png',
        ];


        if (isset($iconMap[$mime])) {
            return $iconMap[$mime];
        }

        $group = explode('/', $mime)[0];
        return $iconMap[$group] ?? 'file.png';
    }
}