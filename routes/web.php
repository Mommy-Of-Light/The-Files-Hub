<?php

use TheFileHub\Controllers\HomeController;
use TheFileHub\Controllers\LoginController;

$app->get('/', [HomeController::class, 'index']);

$app->get('/profile', [HomeController::class, 'profile']);
$app->post('/profile/update-pfp', [HomeController::class, 'updateProfilePicture']);
$app->post('/profile/delete', [HomeController::class, 'deleteAccount']);

$app->get('/login', [LoginController::class, 'showLogin']);
$app->post('/login', [LoginController::class, 'login']);

$app->get('/register', [LoginController::class, 'showRegister']);
$app->post('/register', [LoginController::class, 'register']);

$app->get('/logout', [LoginController::class, 'logout']);

// Secret route
$app->get('/secret', [HomeController::class, 'secret']);
