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

        $user = User::findByUsername($username);

        if ($user && password_verify($password, $user->getPassword())) {
            UserService::connect($user);

            $_SESSION['success'] = "Login successful!";

            return $response
                ->withHeader('Location', '/')
                ->withStatus(302);
        }

        $_SESSION['error'] = "Invalid credentials!";

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

        if (User::findByUsername($username) || User::findByEmail($email)) {
            $_SESSION['error'] = "Username or email already exists.";

            return $response
                ->withHeader('Location', '/register')
                ->withStatus(302);
        }

        $profilePicturePath = null;

        if ($profilePicture && $profilePicture->getError() === UPLOAD_ERR_OK) {
            $mimeType = $profilePicture->getClientMediaType();

            if (!in_array($mimeType, ['image/jpeg', 'image/png'])) {
                $_SESSION['error'] = "Invalid profile picture format.";
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
        }
        else {
            $newFilename = 'default.png';
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        User::create([
            'firstName' => $firstName,
            'lastName' => $lastName,
            'userName' => $username,
            'email' => $email,
            'password' => $hashedPassword,
            'roles' => 0,
            'level' => 0,
            'xp' => 0,
            'profilePicture' => $newFilename,
        ]);

        $_SESSION['success'] = "Registration successful!";

        return $response
            ->withHeader('Location', '/login')
            ->withStatus(302);
    }

    public function logout(Request $request, Response $response): Response
    {
        UserService::disconnect();

        $_SESSION['success'] = "Logout successful!";

        return $response
            ->withHeader('Location', '/login')
            ->withStatus(302);
    }
}