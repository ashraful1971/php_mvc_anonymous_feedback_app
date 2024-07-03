<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Validation;

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
        ]);

        if($validation->failed()){
            flash_message('error', $validation->getMessage());
        }
        
        // store data
        // redirect to login with success msg
        return Response::redirect('/register');
    }
}