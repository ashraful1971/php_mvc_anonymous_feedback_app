<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Validation;
use App\Models\User;

class AuthController
{

    /**
     * Get login page view
     *
     * @return void
     */
    public function loginPage()
    {
        return Response::view('login');
    }

    /**
     * Login the user
     *
     * @param Request $request
     * @return mixed
     */
    public function handleLogin(Request $request): mixed
    {
        $this->validateLoginCredentials($request->all());
        $this->attemptToLogin($request->all());

        return Response::redirect('/dashboard');
    }

    /**
     * Get register page view
     *
     * @return mixed
     */
    public function registerPage(): mixed
    {
        return Response::view('register');
    }

    /**
     * Register new user
     *
     * @param Request $request
     * @return mixed
     */
    public function handleRegister(Request $request): mixed
    {
        $this->validateRegisterCredentials($request->all());
        $this->attemptToRegister($request->all());

        return Response::redirect('/login');
    }

    /**
     * Logout logged in user
     *
     * @param Request $request
     * @return mixed
     */
    public function handleLogout(): mixed
    {
        Auth::logout();

        return Response::redirect('/login');
    }

    /**
     * Validate login credentials
     *
     * @param array $credentials
     * @return mixed
     */
    private function validateLoginCredentials(array $credentials): mixed
    {
        $validation = Validation::make($credentials, [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validation->failed()) {
            flash_message('error', $validation->getMessage());
            return Response::redirect('/login');
        }
    }

    /**
     * Validate register credentials
     *
     * @param array $credentials
     * @return mixed
     */
    private function validateRegisterCredentials(array $credentials): mixed
    {
        $validation = Validation::make($credentials, [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirm'],
        ]);

        if ($validation->failed()) {
            flash_message('error', $validation->getMessage());
            return Response::redirect('/register');
        }
    }

    /**
     * Try to login using the credentials
     *
     * @param array $credentials
     * @return mixed
     */
    private function attemptToLogin(array $credentials): mixed
    {
        $user = User::find('email', $credentials['email']);

        if (!$user || !password_verify($credentials['password'], $user?->password)) {
            flash_message('error', 'Invalid credentials!');
            return Response::redirect('/login');
        }

        Auth::login($user);
    }

    /**
     * Try to register using the credentials
     *
     * @param array $credentials
     * @return mixed
     */
    private function attemptToRegister(array $credentials): mixed
    {
        $credentials['password'] = password_hash($credentials['password'], PASSWORD_DEFAULT);

        // store data if not user already exist
        if (User::find('email', $credentials['email'])) {
            flash_message('error', 'An account already exist with this email.');
        } else {
            // store data
            User::create($credentials);
            flash_message('success', 'Your account was created successfully!');
        }
    }
}
