<?php

use TheFileHub\Controllers\HomeController;
use TheFileHub\Controllers\LoginController;
use TheFileHub\Controllers\ModeratorController;

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

// Secret route
$app->get('/secret', [HomeController::class, 'secret']);
