<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Validation;
use App\Models\User;

class AuthController {

    public function loginPage()
    {
        return Response::view('login');
    }
    
    public function handleLogin()
    {
        flash_message('success', 'How are you?');
        return Response::redirect('/login');
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
}