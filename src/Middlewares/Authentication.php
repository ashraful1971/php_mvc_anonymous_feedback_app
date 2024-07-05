<?php

namespace App\Middlewares;

use App\Core\Auth;
use App\Core\Contracts\Middleware;
use App\Core\Request;
use App\Core\Response;

class Authentication implements Middleware {
    public static function handle(Request $request)
    {
        if(!Auth::isAuthenticated()){
            return Response::redirect('/login');
        }

        $request->user = Auth::user();
    }
}