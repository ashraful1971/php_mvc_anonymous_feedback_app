<?php

namespace App\Core;

class Response {
    /**
     * Send json response to the client
     *
     * @param array $data
     * @return void
     */
    public static function json(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Send the html view file as a response
     *
     * @param string $file
     * @param array $data
     * @return void
     */
    public static function view(string $file, array $data=[]): void
    {
        header('Content-Type: text/html');
        require_once VIEW_PATH.'/'.$file.'.php';
        exit;
    }
    
    /**
     * Redirect the user to the specified link
     *
     * @param string $url
     * @return void
     */
    public static function redirect(string $url): void
    {
        header('Location: ' . url($url));
        exit;
    }
}