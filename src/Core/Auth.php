<?php

namespace App\Core;

class Auth {

    public static function user()
    {
        return isset($_SESSION['user']) ? $_SESSION['user'] : null;
    }
    
    public static function isAuthenticated()
    {
        return isset($_SESSION['user']);
    }

    public static function login($userInfo)
    {
        $_SESSION['user'] = $userInfo;
    }
    
    public static function logout()
    {
        unset($_SESSION['user']);
        redirect('/');
    }
}