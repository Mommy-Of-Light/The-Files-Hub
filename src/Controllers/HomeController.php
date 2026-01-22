<?php

declare(strict_types=1);

namespace TheFileHub\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

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
            return $response
                ->withHeader('Location', '/login')
                ->withStatus(302);
        }

        return $this->view->render($response, 'home/home.php', [
            'title' => 'TheFileHub | Home',
        ]);
    }

    public function profile(Request $request, Response $response): Response
    {
        if (!UserService::isConnected()) {
            return $response
                ->withHeader('Location', '/login')
                ->withStatus(302);
        }

        return $this->view->render($response, 'home/profile.php', [
            'title' => 'TheFileHub | Profile',
            'user' => UserService::current(),
        ]);
    }

    public function updateProfilePicture(Request $request, Response $response): Response
    {
        if (!UserService::isConnected()) {
            return $response
                ->withHeader('Location', '/login')
                ->withStatus(302);
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
            return $response
                ->withHeader('Location', '/login')
                ->withStatus(302);
        }

        $user = UserService::current();

        $user->delete();
        UserService::disconnect();

        return $response->withHeader('Location', '/profile')->withStatus(302);
    }
}