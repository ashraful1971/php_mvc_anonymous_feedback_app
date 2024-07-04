<?php

namespace App\Core;

class Response {
    public static function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    public static function view($file, $data=[])
    {
        header('Content-Type: text/html');
        require_once VIEW_PATH.'/'.$file.'.php';
        exit;
    }
    
    public static function redirect($url)
    {
        header('Location: ' . url($url));
    }
}