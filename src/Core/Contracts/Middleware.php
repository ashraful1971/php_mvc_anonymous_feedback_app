<?php

namespace App\Core\Contracts;

use App\Core\Request;

interface Middleware {
    public static function handle(Request $request);
}