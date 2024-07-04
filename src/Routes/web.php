<?php

use App\Controllers\AuthController;
use App\Controllers\PageController;
use App\Core\Route;
use App\Middlewares\Authentication;
use App\Middlewares\Guest;

Route::get('/', [PageController::class, 'index']);
Route::get('/404', [PageController::class, 'pageNotFound']);

Route::get('/dashboard', [PageController::class, 'dashboard'], [Authentication::class]);
Route::get('/feedback', [PageController::class, 'feedback']);
Route::post('/feedback', [PageController::class, 'handleFeedback']);
Route::get('/feedback-success', [PageController::class, 'feedbackSuccess']);

Route::get('/login', [AuthController::class, 'loginPage'], [Guest::class]);
Route::post('/login', [AuthController::class, 'handleLogin'], [Guest::class]);
Route::get('/register', [AuthController::class, 'registerPage'], [Guest::class]);
Route::post('/register', [AuthController::class, 'handleRegister'], [Guest::class]);
Route::get('/logout', [AuthController::class, 'handleLogout', [Authentication::class]]);