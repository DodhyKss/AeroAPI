<?php

namespace App\Commands;

use Core\Command;
use Core\Application;

class MigrateCommand extends Command
{
    public function execute(array $args)
    {
        $db = Application::$app->db;
        $pdo = $db->pdo;
        
        // Get active database driver
        $driver = $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);

        // Buat tabel migrations jika belum ada sesuai driver
        if ($driver === 'sqlite') {
            $pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                migration VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );");
        } elseif ($driver === 'pgsql') {
            $pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
                id SERIAL PRIMARY KEY,
                migration VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );");
        } else { // mysql
            $pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=INNODB;");
        }

        $statement = $pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();
        $appliedMigrations = $statement->fetchAll(\PDO::FETCH_COLUMN);

        $migrationDir = Application::$ROOT_DIR . '/migrations';
        if (!is_dir($migrationDir)) {
            mkdir($migrationDir, 0777, true);
        }
        
        $files = scandir($migrationDir);
        $newMigrations = [];

        foreach ($files as $file) {
            // Hanya proses file .sql utama, abaikan driver-specific suffix dalam scan awal
            if ($file === '.' || $file === '..' || pathinfo($file, PATHINFO_EXTENSION) !== 'sql') {
                continue;
            }

            // Lewati file driver-specific (*.sqlite.sql, *.mysql.sql)
            if (preg_match('/\.(sqlite|mysql|pgsql)\.sql$/', $file)) {
                continue;
            }
            
            if (!in_array($file, $appliedMigrations)) {
                $this->log("Applying migration $file...");
                
                // Cari file sql sesuai driver, jika tidak ada gunakan default .sql
                $sqlFile = $migrationDir . '/' . $file;
                $driverSpecificFile = str_replace('.sql', '.' . $driver . '.sql', $sqlFile);
                if (file_exists($driverSpecificFile)) {
                    $sql = file_get_contents($driverSpecificFile);
                } else {
                    $sql = file_get_contents($sqlFile);
                }

                if (!empty(trim($sql))) {
                    $pdo->exec($sql);
                }
                $this->log("Applied migration $file");
                $newMigrations[] = $file;
            }
        }

        if (!empty($newMigrations)) {
            $str = implode(",", array_map(fn($m) => "('$m')", $newMigrations));
            $pdo->prepare("INSERT INTO migrations (migration) VALUES $str")->execute();
        } else {
            $this->log("Semua migration sudah diaplikasikan.");
        }
    }
}
