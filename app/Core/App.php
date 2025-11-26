<?php
// app/Core/App.php

class App {
    private $controller = 'AuthController';
    private $method = 'login';
    private $params = [];

    public function __construct() {
        $url = $this->parseUrl();
        
        // Check if controller exists
        if (isset($url[0]) && file_exists('../app/Controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            $this->method = 'index';
            unset($url[0]);
        }

        require_once '../app/Controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Check if method exists
        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        // Get params
        $this->params = $url ? array_values($url) : [];

        // Call the controller method with params
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
