<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Validation;
use App\Models\User;

class AuthController {

    public function loginPage()
    {
        return Response::view('login');
    }
    
    public function handleLogin(Request $request)
    {
        // validation
        $validation = Validation::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if($validation->failed()){
            flash_message('error', $validation->getMessage());
            return Response::redirect('/login');
        }

        if(!$user = User::find('email', $request->email)){
            flash_message('error', 'Invalid credentials!');
            return Response::redirect('/login');
        }

        if(!password_verify($request->password, $user->password)){
            flash_message('error', 'Invalid credentials!');
            return Response::redirect('/login');
        }

        Auth::login($user);
        return Response::redirect('/dashboard');
    }

    public function registerPage()
    {
        return Response::view('register');
    }
    
    public function handleRegister(Request $request)
    {
        // validation
        $validation = Validation::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirm'],
        ]);

        if($validation->failed()){
            flash_message('error', $validation->getMessage());
            return Response::redirect('/register');
        }

        // hash password
        $request->password = password_hash($request->password, PASSWORD_DEFAULT);

        // store data if not user already exist
        if(User::find('email', $request->email)){
            flash_message('error', 'An account already exist with this email.');
        } else {
            // store data
            User::create($request->all());
            flash_message('success', 'Your account was created successfully!');
        }

        return Response::redirect('/login');
    }

    public function handleLogout()
    {
        Auth::logout();
        
        return Response::redirect('/login');
    }
}