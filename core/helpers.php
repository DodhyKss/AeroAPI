<?php

use Core\Application;

if (!function_exists('view')) {
    /**
     * @param string $name
     * @param array $params
     * @return string
     */
    function view(string $name, array $params = []) {
        return Application::$app->router->renderView($name, $params);
    }
}

if (!function_exists('response')) {
    /**
     * @return \Core\Response
     */
    function response() {
        return Application::$app->response;
    }
}

if (!function_exists('request')) {
    /**
     * @return \Core\Request
     */
    function request() {
        return Application::$app->request;
    }
}

if (!function_exists('app')) {
    /**
     * @return \Core\Application
     */
    function app() {
        return Application::$app;
    }
}

if (!function_exists('env')) {
    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function env(string $key, $default = null) {
        return $_ENV[$key] ?? $_SERVER[$key] ?? $default;
    }
}

if (!function_exists('abort')) {
    /**
     * @param int $code
     * @param string $message
     * @return void
     */
    function abort(int $code, string $message = '') {
        http_response_code($code);
        
        $headers = request()->getHeaders();
        $accept = $headers['Accept'] ?? '';
        $path = request()->getPath();
        
        if (strpos($accept, 'application/json') !== false || strpos($path, '/api/') === 0) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'code' => $code,
                'message' => $message ?: 'Terjadi kesalahan pada server.'
            ]);
            exit;
        }

        echo view('error', ['message' => $message ?: "Error $code"]);
        exit;
    }
}
