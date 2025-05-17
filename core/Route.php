<?php
class Route
{
    public static function __callStatic($name, $arguments)
    {
        if (strtoupper($name) != strtoupper($_SERVER['REQUEST_METHOD'])) {
            return;
        }
        
        $url = $arguments[0];
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        $requestPath = parse_url($requestUri, PHP_URL_PATH);
        
        $url = trim($url, '/');
        $requestPath = trim($requestPath, '/');
        
        if ($url != $requestPath) {
            return;
        }

        list($class, $method) = $arguments[1];

        $controller = new $class();
        $controller->$method();
    }
}