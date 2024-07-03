<?php

use App\Controllers\AuthController;
use App\Controllers\PageController;
use App\Core\Route;
use App\Core\Response;

Route::get('/', [PageController::class, 'index']);
Route::get('/login', [AuthController::class, 'loginPage']);
Route::post('/login', [AuthController::class, 'handleLogin']);
Route::get('/register', [AuthController::class, 'registerPage']);
Route::post('/register', [AuthController::class, 'handleRegister']);

Route::get('/dashboard', function(){
    return Response::view('dashboard');
});

Route::get('/feedback', function(){
    return Response::view('feedback');
});

Route::get('/feedback-success', function(){
    return Response::view('feedback-success');
});