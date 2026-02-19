<?php

declare(strict_types=1);

namespace TheFileHub\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use TheFileHub\Models\Post;
use TheFileHub\Services\UserService;

class HomeController extends BaseController
{
    /**
     * Show home page
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function index(Request $request, Response $response): Response
    {
        if (!UserService::isConnected()) {
            return UserService::unAuthorized($response, $request, $this->view);
        }

        $allPosts = Post::All();

        foreach ($allPosts as $post) {
            $post->fileExt = PostController::fromPath($post->fileLink);
        }

        usort($allPosts, function ($a, $b) {
            $aTotal = $a->likes - $a->dislikes;
            $bTotal = $b->likes - $b->dislikes;

            return $bTotal <=> $aTotal;
        });

        return $this->view->render($response, 'home/home.php', [
            'title' => 'TheFileHub | Home',
            'topPosts' => $allPosts
        ]);
    }

    public function profile(Request $request, Response $response): Response
    {
        if (!UserService::isConnected()) {
            return UserService::unAuthorized($response, $request, $this->view);
        }

        $user = UserService::current();

        $currentLevel = $user->level;
        $currentExp = $user->xp;
        $maxed = false;

        $nextLevelXpRequierd = (($currentLevel + 1) * 500);

        if ($currentExp >= $nextLevelXpRequierd && $currentLevel < 99) {
            $user->level += 1;
            $user->xp = 0;
            $nextLevelXpRequierd = (($currentLevel + 1) * 500);
            $user->save();
        } elseif ($currentLevel == 99 && $currentExp >= $nextLevelXpRequierd) {
            $maxed = true;
        }

        return $this->view->render($response, 'home/profile.php', [
            'title' => 'TheFileHub | Profile',
            'user' => $user,
            'nextLevelXpRequierd' => $nextLevelXpRequierd,
            'isMaxed' => $maxed
        ]);
    }

    public function updateProfilePicture(Request $request, Response $response): Response
    {
        if (!UserService::isConnected()) {
            return UserService::unAuthorized($response, $request, $this->view);
        }

        $uploadedFile = $request->getUploadedFiles()['pfp'] ?? null;

        if (!$uploadedFile) {
            $_SESSION['error'] = 'No file uploaded.';
            return $response->withHeader('Location', '/profile')->withStatus(302);
        }

        if ($uploadedFile->getError() === UPLOAD_ERR_INI_SIZE) {
            $_SESSION['error'] = 'Profile picture is too large (max 10MB).';
            return $response->withHeader('Location', '/profile')->withStatus(302);
        }

        if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Upload failed.';
            return $response->withHeader('Location', '/profile')->withStatus(302);
        }

        $maxSize = 10 * 1024 * 1024;
        if ($uploadedFile->getSize() > $maxSize) {
            $_SESSION['error'] = 'Profile picture is too large (max 10MB).';
            return $response->withHeader('Location', '/profile')->withStatus(302);
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/bmp'];
        if (!in_array($uploadedFile->getClientMediaType(), $allowedTypes)) {
            $_SESSION['error'] = 'Invalid image format.';
            return $response->withHeader('Location', '/profile')->withStatus(302);
        }

        $user = UserService::current();

        $directory = __DIR__ . '/../../public/assets/pfp';

        foreach (glob($directory . '/' . $user->userName . '_pfp.*') as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        $extension = strtolower(pathinfo(
            $uploadedFile->getClientFilename(),
            PATHINFO_EXTENSION
        ));

        $filename = sprintf('%s_pfp.%s', $user->userName, $extension);

        $uploadedFile->moveTo($directory . '/' . $filename);

        $user->profilePicture = $filename;
        $_SESSION['user'] = $user;
        $user->save();

        $_SESSION['success'] = 'Profile picture updated successfully.';

        return $response
            ->withHeader('Location', '/profile')
            ->withStatus(302);
    }

    public function deleteAccount(Request $request, Response $response): Response
    {
        if (!UserService::isConnected()) {
            return UserService::unAuthorized($response, $request, $this->view);
        }

        $user = UserService::current();

        $user->delete();
        UserService::disconnect();

        return $response->withHeader('Location', '/profile')->withStatus(302);
    }

    public function secret(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'errors/418.php', [
            'title' => 'TheFileHub | Secret',
            'withMenu' => false,
        ]);
    }
}