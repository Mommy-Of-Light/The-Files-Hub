<?php

declare(strict_types=1);

namespace TheFileHub\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use TheFileHub\Models\User;
use TheFileHub\Services\UserService;

class LoginController extends BaseController
{
    /**
     * Show login page
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function showLogin(Request $request, Response $response): Response
    {
        if (UserService::isConnected()) {
            return $response
                ->withHeader('Location', '/')
                ->withStatus(302);
        }

        return $this->view->render($response, 'login/login.php', [
            'title' => 'TheFileHub | Login',
            'withMenu' => true,
        ]);
    }

    public function login(Request $request, Response $response): Response
    {
        $data = (array) $request->getParsedBody();
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        // Find user by username
        $user = User::findByUsername($username);

        if ($user && password_verify($password, $user->getPassword())) {
            // Connect user
            UserService::connect($user);

            return $response
                ->withHeader('Location', '/')
                ->withStatus(302);
        }

        // Invalid credentials, redirect back to login
        return $response
            ->withHeader('Location', '/login')
            ->withStatus(302);
    }

    public function showRegister(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'login/register.php', [
            'title' => 'TheFileHub | Register',
            'withMenu' => true,
        ]);
    }

    public function register(Request $request, Response $response): Response
    {
        $data = (array) $request->getParsedBody();
        $uploadedFiles = $request->getUploadedFiles();

        $firstName = $data['firstName'] ?? '';
        $lastName = $data['lastName'] ?? '';
        $username = $data['username'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        /** @var \Psr\Http\Message\UploadedFileInterface|null $profilePicture */
        $profilePicture = $uploadedFiles['pfp'] ?? null;

        // Check if username or email already exists
        if (User::findByUsername($username) || User::findByEmail($email)) {
            return $response
                ->withHeader('Location', '/register')
                ->withStatus(302);
        }

        $profilePicturePath = null;

        // Validate & save profile picture
        if ($profilePicture && $profilePicture->getError() === UPLOAD_ERR_OK) {
            $mimeType = $profilePicture->getClientMediaType();

            if (!in_array($mimeType, ['image/jpeg', 'image/png'])) {
                return $response
                    ->withHeader('Location', '/register')
                    ->withStatus(302);
            }

            $extension = pathinfo(
                $profilePicture->getClientFilename(),
                PATHINFO_EXTENSION
            );

            $newFilename = sprintf('%s_pfp.%s', $username, $extension);

            $profilePicture->moveTo(
                __DIR__ . '/../../public/assets/pfp/' . $newFilename
            );

            $profilePicturePath = '/assets/pfp/' . $newFilename;
        }

        // Create user
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        User::create([
            'firstName' => $firstName,
            'lastName' => $lastName,
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
            'profilePicture' => $profilePicturePath,
        ]);

        return $response
            ->withHeader('Location', '/login')
            ->withStatus(302);
    }

    public function logout(Request $request, Response $response): Response
    {
        UserService::disconnect();

        return $response
            ->withHeader('Location', '/login')
            ->withStatus(302);
    }
}