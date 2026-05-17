<?php

namespace App\Commands;

use Core\Command;
use Core\Application;

class SeedCommand extends Command
{
    public function execute(array $args)
    {
        $db = Application::$app->db;
        $pdo = $db->pdo;

        $seederDir = Application::$ROOT_DIR . '/seeders';
        if (!is_dir($seederDir)) {
            mkdir($seederDir, 0777, true);
        }
        
        $files = scandir($seederDir);
        $executed = 0;

        foreach ($files as $file) {
            if ($file === '.' || $file === '..' || pathinfo($file, PATHINFO_EXTENSION) !== 'sql') {
                continue;
            }
            
            $this->log("Running seeder $file...");
            $sql = file_get_contents($seederDir . '/' . $file);
            if (!empty(trim($sql))) {
                $pdo->exec($sql);
            }
            $this->log("Ran seeder $file");
            $executed++;
        }

        if ($executed === 0) {
            $this->log("Tidak ada file .sql di folder seeders.");
        }
    }
}
