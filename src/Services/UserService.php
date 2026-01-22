<?php

declare(strict_types=1);

namespace TheFileHub\Services;

use TheFileHub\Models\User;

class UserService
{
    /**
     * Get the current user
     *
     * @return User|null
     */
    public static function current(): ?User
    {
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }

        return null;
    }

    /**
     * Return whether the user is connected or not
     *
     * @return boolean
     */
    public static function isConnected(): bool
    {
        return static::current() instanceof User;
    }

    /**
     * Store user in session
     *
     * @param User $user
     * @return void
     */
    public static function connect(User $user): void
    {
        // Put in session
        $_SESSION['user'] = $user;
        session_regenerate_id(true);
    }

    /**
     * Disconnect user
     *
     * @return void
     */
    public static function disconnect(): void
    {
        // Remove from session
        unset($_SESSION['user']);
        session_regenerate_id(true);
    }

    public static function getAllUsers(): array
    {
        return User::All();
    }

    public static function unAuthorized(\Psr\Http\Message\ResponseInterface $response, \Psr\Http\Message\ServerRequestInterface $request, \Slim\Views\PhpRenderer $view): \Psr\Http\Message\ResponseInterface
    {
        $view->setLayout('layout.php');

        return $view->render(
            $response->withStatus(401),
            'errors/401.php',
            [
                'withMenu' => false,
                'title' => 'Unauthorized',
                'message' => 'You must be logged in to access this page.'
            ]
        );
    }
}