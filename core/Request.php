<?php

namespace Core;

class Request
{
    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position === false) {
            return $path;
        }
        return substr($path, 0, $position);
    }

    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function getBody()
    {
        $body = [];
        
        // Selalu sertakan query parameters (?key=val) dari URL
        foreach ($_GET as $key => $value) {
            $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }
        
        $method = $this->getMethod();
        if ($method !== 'get') {
            $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
            if (strpos($contentType, 'application/json') !== false) {
                $content = trim(file_get_contents("php://input"));
                $decoded = json_decode($content, true);
                if (is_array($decoded)) {
                    $body = array_merge($body, $decoded);
                }
            } else {
                if ($method === 'post') {
                    foreach ($_POST as $key => $value) {
                        $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                } else {
                    parse_str(file_get_contents("php://input"), $parsedParams);
                    foreach ($parsedParams as $key => $value) {
                        $body[$key] = filter_var($value, FILTER_DEFAULT); // keep intact
                    }
                }
            }
        }
        return $body;
    }

    public function getHeaders()
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) == 'HTTP_') {
                $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
                $headers[$header] = $value;
            }
        }
        return $headers;
    }
}
