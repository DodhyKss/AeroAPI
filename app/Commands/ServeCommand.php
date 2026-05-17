<?php

namespace App\Commands;

use Core\Command;
use Core\Application;

class ServeCommand extends Command
{
    public function execute(array $args)
    {
        $port = 8000; // Default port

        // Parse arguments (e.g. php aero serve 8080 or php aero serve --port=8080)
        foreach ($args as $arg) {
            if (is_numeric($arg)) {
                $port = (int)$arg;
            } elseif (strpos($arg, '--port=') === 0) {
                $port = (int)substr($arg, 7);
            }
        }

        $this->log("Memulai server development Aero di http://localhost:$port");
        $this->log("Document root: " . Application::$ROOT_DIR . "/public");
        $this->log("Gunakan Ctrl+C untuk menghentikan server.");

        // Jalankan built-in server PHP
        passthru("php -S localhost:$port -t public");
    }
}
