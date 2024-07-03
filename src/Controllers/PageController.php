<?php

namespace App\Controllers;

use App\Core\Response;

class PageController {
    public function index()
    {
        return Response::view('home');
    }
}