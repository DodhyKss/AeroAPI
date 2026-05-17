<?php

namespace Core;

class Response
{
    public function setStatusCode(int $code)
    {
        http_response_code($code);
    }

    public function json(array $data, int $statusCode = 200)
    {
        $this->setStatusCode($statusCode);
        header('Content-Type: application/json');
        
        if (defined('AERO_START')) {
            $ms = number_format((microtime(true) - AERO_START) * 1000, 2);
            header("X-Execution-Time: {$ms}ms");
            
            // Inject debug metrics into payload if APP_DEBUG is enabled
            if (filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
                $data['_debug'] = [
                    'execution_time' => "{$ms}ms"
                ];
            }
        }
        
        echo json_encode($data);
        exit;
    }
}
