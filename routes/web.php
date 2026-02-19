<?php

use TheFileHub\Controllers\HomeController;
use TheFileHub\Controllers\LoginController;
use TheFileHub\Controllers\ModeratorController;
use TheFileHub\Controllers\PostController;

$app->get('/', [HomeController::class, 'index']);

$app->get('/profile', [HomeController::class, 'profile']);
$app->post('/profile/update-pfp', [HomeController::class, 'updateProfilePicture']);
$app->post('/profile/delete', [HomeController::class, 'deleteAccount']);

$app->get('/login', [LoginController::class, 'showLogin']);
$app->post('/login', [LoginController::class, 'login']);

$app->get('/register', [LoginController::class, 'showRegister']);
$app->post('/register', [LoginController::class, 'register']);

$app->get('/logout', [LoginController::class, 'logout']);

$app->get('/mod', [ModeratorController::class, 'index']);
$app->get('/mod/admin-dashboard', [ModeratorController::class, 'adminDashboard']);
$app->get('/mod/opperator-dashboard', [ModeratorController::class, 'opperatorDashboard']);

$app->post('/mod/user/promote/{id}', [ModeratorController::class, 'promoteUser']);
$app->post('/mod/user/demote/{id}', [ModeratorController::class, 'demoteUser']);
$app->post('/mod/user/delete/{id}', [ModeratorController::class, 'deleteUser']);

$app->get('/posts', callable: [PostController::class, 'all']);
$app->get('/post/new', [PostController::class, 'new']);
$app->post('/post/new', [PostController::class, 'submit']);
// $app->get('/post/single/debbug', [PostController::class, 'single']);
$app->get('/post/single/{id:\d+}', [PostController::class, 'single']);
$app->post('/post/single/{id:\d+}/update', [PostController::class, 'singleUpdate']);
$app->post('/post/single/{id:\d+}/delete', [PostController::class, 'singleDelete']);
$app->post('/post/single/{id:\d+}/{type}', [PostController::class, 'gestionLikes']);

// Secret route
$app->get('/secret', [HomeController::class, 'secret']);
