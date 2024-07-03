<?php

// auto loader
require_once 'vendor/autoload.php';

// required files
require_once 'src/Configs/app.php';
require_once 'src/Libs/functions.php';
require_once 'src/Routes/web.php';

// run app
App\Core\Application::init()
    ->withRoutes(App\Core\Route::collection())    
    ->run(new App\Core\Request());

