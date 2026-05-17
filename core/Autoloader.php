<?php

// Load Composer Autoloader
$composerAutoload = dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists($composerAutoload)) {
    require_once $composerAutoload;
}

spl_autoload_register(function ($class) {
    // Pemetaan prefix namespace
    $prefixes = [
        'Core\\' => 'core/',
        'App\\' => 'app/',
    ];

    foreach ($prefixes as $prefix => $base_dir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }

        $relative_class = substr($class, $len);
        $file = dirname(__DIR__) . '/' . $base_dir . str_replace('\\', '/', $relative_class) . '.php';

        if (file_exists($file)) {
            require $file;
            return;
        }
    }
});

// Load Global Helpers
$helpersFile = dirname(__DIR__) . '/core/helpers.php';
if (file_exists($helpersFile)) {
    require_once $helpersFile;
}
