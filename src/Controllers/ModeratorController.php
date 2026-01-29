<?php

declare(strict_types=1);

namespace TheFileHub\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use TheFileHub\Services\UserService;
use TheFileHub\Models\User;

class ModeratorController extends BaseController
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

        $role = UserService::current()->getRoles();

        if ($role === 1) {
            $role = 'admin';
        } elseif ($role == 2) {
            $role = 'opperator';
        } else {
            // Not authorized
            return $response
                ->withHeader('Location', '/')
                ->withStatus(302);
        }
        
        return $response
            ->withHeader('Location', '/mod/' . $role . '-dashboard')
            ->withStatus(302);
    }

    public function adminDashboard(Request $request, Response $response): Response
    {
        if (!UserService::isConnected()) {
            return UserService::unAuthorized($response, $request, $this->view);
        }
        return $this->view->render($response, 'roles/moderators.php', [
            'title' => 'TheFileHub | Admin Dashboard',
            'role' => 'admin',
            'users' => UserService::getAllUsers(),
            'posts' => ['dummydata' => 'value'], // Placeholder for POST data if needed
        ]);
    }

        public function opperatorDashboard(Request $request, Response $response): Response
    {
        if (!UserService::isConnected()) {
            return UserService::unAuthorized($response, $request, $this->view);
        }
        return $this->view->render($response, 'roles/moderators.php', [
            'title' => 'TheFileHub | Opperator Dashboard',
            'role' => 'opperator',
            'users' => UserService::getAllUsers(),
            'posts' => ['dummydata' => 'value'], // Placeholder for POST data if needed
        ]);
    }

    public function promoteUser(Request $request, Response $response, array $args): Response
    {
        $userId = (int) $args['id'];

        $user = User::findById($userId);
        if ($user) {
            if (UserService::current()->idUser === $user->idUser) {
                // Prevent self-promotion
                return $response
                    ->withHeader('Location', '/mod/admin-dashboard')
                    ->withStatus(302);
            }
            $user->setRoles(min(2, $user->getRoles() + 1)); // Set role to operator
            $user->save();
        }

        $_SESSION['user'] = UserService::current();

        return $response
            ->withHeader('Location', '/mod/admin-dashboard')
            ->withStatus(302);
    }

    public function demoteUser(Request $request, Response $response, array $args): Response
    {
        $userId = (int) $args['id'];

        $user = User::findById($userId);
        if ($user) {
            if (UserService::current()->idUser === $user->idUser) {
                // Prevent self-demotion
                return $response
                    ->withHeader('Location', '/mod/admin-dashboard')
                    ->withStatus(302);
            }
            if ($user->getRoles() === 1) {
                // Prevent demoting admins
                return $response
                    ->withHeader('Location', '/mod/admin-dashboard')
                    ->withStatus(302);
            }
            if ($user->getRoles() === 3) {
                // Prevent demoting creators
                return $response
                    ->withHeader('Location', '/mod/admin-dashboard')
                    ->withStatus(302);
            }
            $user->setRoles(min(2, $user->getRoles() - 1)); // Set role to regular user
            $user->save();
        }

        $_SESSION['user'] = UserService::current();

        return $response
            ->withHeader('Location', '/mod/admin-dashboard')
            ->withStatus(302);
    }

    public function deleteUser(Request $request, Response $response, array $args): Response
    {
        $userId = (int) $args['id'];

        $user = User::findById($userId);
        if ($user) {
            $user->delete();
        }

        return $response
            ->withHeader('Location', '/mod/admin-dashboard')
            ->withStatus(302);
    }
}