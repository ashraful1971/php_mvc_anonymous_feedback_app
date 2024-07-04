<?php

function dd($data){
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    exit;
}

function public_path($path){
    return PUBLIC_PATH.$path;
}

function component($path){
    require_once VIEW_PATH.'/components/'.$path.'.php';
}

function __($value){
    echo $value;
}

function url($endpoint, $params = []) {
    $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    $baseUrl = strtok($baseUrl, "?");

    $base_endpoint = dirname($_SERVER['SCRIPT_NAME']);
    $base_endpoint = str_replace("\\", '/', $base_endpoint);

    $queryString = http_build_query($params);

    $url = $baseUrl . preg_replace('#/+#', '/', ($base_endpoint . '/' . $endpoint));

    if ($queryString) {
      $url .= '?' . $queryString;
    }

    return rtrim($url, '/');
  }

  function flash_message($key, $value=null){
    if(isset($key) && isset($value)){
        $_SESSION[$key] = $value;
        return;
    }

    $value = isset($_SESSION[$key]) ? $_SESSION[$key] : null;

    unset($_SESSION[$key]);

    return $value;
  }

  function generateUniqueId(): string {
    $prefix = substr(md5(uniqid(mt_rand(), true)), 0, 5);
    $suffix = random_int(100000, 999999);
    return $prefix . $suffix;
  }