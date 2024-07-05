<?php

namespace App\Core;

use App\Core\Model;

class Auth {

    /**
     * Get the authenticated user
     *
     * @return Model|null
     */
    public static function user(): Model|null
    {
        return isset($_SESSION['user']) ? $_SESSION['user'] : null;
    }
    
    /**
     * Check if the user is authenticated
     *
     * @return boolean
     */
    public static function isAuthenticated(): bool
    {
        return isset($_SESSION['user']);
    }

    /**
     * Login the user
     *
     * @param Model $userInfo
     * @return void
     */
    public static function login(Model $userInfo): void
    {
        $_SESSION['user'] = $userInfo;
    }
    
    /**
     * Logout the authenticated user
     *
     * @return void
     */
    public static function logout(): void
    {
        unset($_SESSION['user']);
        redirect('/');
    }
}