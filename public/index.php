<?php
define('AERO_START', microtime(true));

// Serve static files when running with PHP's built-in web server
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if ($path !== false && is_file($path)) {
        return false;
    }
}

require_once dirname(__DIR__) . '/core/Autoloader.php';

$app = new \Core\Application(dirname(__DIR__));

require_once dirname(__DIR__) . '/routes/web.php';

$app->run();
