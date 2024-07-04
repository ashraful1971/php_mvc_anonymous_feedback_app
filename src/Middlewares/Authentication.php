<?php

namespace App\Middlewares;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;

class Authentication {
    public static function handle(Request $request)
    {
        if(!Auth::isAuthenticated()){
            return Response::redirect('/login');
        }

        $request->user = Auth::user();
    }
}