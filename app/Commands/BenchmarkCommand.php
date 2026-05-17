<?php

namespace App\Commands;

use Core\Command;
use Core\Application;

class BenchmarkCommand extends Command
{
    public function execute(array $args)
    {
        $testPath = $args[2] ?? null; // $args[0] is aero, $args[1] is benchmark, $args[2] is target
        $testDir = Application::$ROOT_DIR . '/tests/api';
        
        // Auto create folder tests/api if not exists and populate defaults
        if (!is_dir($testDir)) {
            mkdir($testDir, 0777, true);
            $this->createDefaultTests($testDir);
        }

        $allFiles = [];
        $files = scandir($testDir);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..' || pathinfo($file, PATHINFO_EXTENSION) !== 'php') continue;
            $allFiles[] = $testDir . '/' . $file;
        }

        $filesToTest = [];

        // Check if a specific argument is passed (either number or file path)
        if ($testPath) {
            if (is_numeric($testPath)) {
                $index = (int)$testPath;
                if ($index === 0) {
                    $filesToTest = $allFiles;
                } elseif (isset($allFiles[$index - 1])) {
                    $filesToTest[] = $allFiles[$index - 1];
                } else {
                    $this->logError("Indeks pilihan benchmark tidak valid: $testPath");
                    exit(1);
                }
            } else {
                $fullPath = Application::$ROOT_DIR . '/' . ltrim($testPath, '/');
                if (file_exists($fullPath)) {
                    $filesToTest[] = $fullPath;
                } else {
                    // Try matching relative to tests/api
                    $altPath = $testDir . '/' . basename($testPath);
                    if (file_exists($altPath)) {
                        $filesToTest[] = $altPath;
                    } else {
                        $this->logError("File test tidak ditemukan di path: $testPath");
                        exit(1);
                    }
                }
            }
        } else {
            // Interactive STDIN Prompt Menu
            if (count($allFiles) > 1) {
                $this->log("====================================================");
                $this->log("   AeroAPI BENCHMARKING & SPEED TESTER TOOL");
                $this->log("====================================================");
                $this->log("Ditemukan beberapa berkas pengujian API:");
                $this->log(" [0] Jalankan Semua File (Run All Tests)");
                
                $menuOptions = [];
                $index = 1;
                foreach ($allFiles as $file) {
                    $config = include $file;
                    $name = is_array($config) && isset($config['name']) ? $config['name'] : basename($file);
                    $this->log(sprintf(" [%d] %s (%s)", $index, $name, basename($file)));
                    $menuOptions[$index] = $file;
                    $index++;
                }
                $this->log("");
                echo "Pilih nomor file yang ingin dijalankan (0-" . count($allFiles) . "): ";
                
                $input = trim(fgets(STDIN));
                $selectedIndex = is_numeric($input) ? (int)$input : -1;

                if ($selectedIndex === 0) {
                    $filesToTest = $allFiles;
                } elseif (isset($menuOptions[$selectedIndex])) {
                    $filesToTest[] = $menuOptions[$selectedIndex];
                } else {
                    $this->logWarning("Pilihan tidak valid. Membatalkan eksekusi benchmark.");
                    return;
                }
            } else {
                $filesToTest = $allFiles;
            }
        }

        if (empty($filesToTest)) {
            $this->logWarning("Tidak ada file pengujian (.php) yang ditemukan di folder tests/api/");
            return;
        }

        $this->log("====================================================");
        $this->log("   AeroAPI BENCHMARKING & SPEED TESTER TOOL");
        $this->log("====================================================");
        $this->log("Mendeteksi " . count($filesToTest) . " berkas pengujian API...");
        $this->log("");

        foreach ($filesToTest as $file) {
            $config = include $file;
            if (!is_array($config)) {
                $this->logWarning("Lewati " . basename($file) . ": format file pengujian tidak valid.");
                continue;
            }

            $name = $config['name'] ?? basename($file);
            $url = $config['url'] ?? '';
            $method = strtoupper($config['method'] ?? 'GET');
            $headers = $config['headers'] ?? [];
            $body = $config['body'] ?? null;
            $requestCount = $config['requests'] ?? 10;

            if (!$url) {
                $this->logWarning("Lewati '$name': URL tidak didefinisikan.");
                continue;
            }

            $this->log("   Menjalankan Test: \033[1;36m$name\033[0m");
            $this->log("   Target: $method $url");
            $this->log("   Total Request: $requestCount");
            $this->log("   Sedang memproses... ");

            $latencies = [];
            $successCount = 0;
            $failedCount = 0;
            $serverExecutionTimes = [];

            for ($i = 1; $i <= $requestCount; $i++) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
                curl_setopt($ch, CURLOPT_HEADER, true); // To parse custom headers

                $curlHeaders = [];
                foreach ($headers as $k => $v) {
                    $curlHeaders[] = "$k: $v";
                }
                curl_setopt($ch, CURLOPT_HTTPHEADER, $curlHeaders);

                if (($method === 'POST' || $method === 'PUT') && $body) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
                }

                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

                $startTime = microtime(true);
                $response = curl_exec($ch);
                $duration = (microtime(true) - $startTime) * 1000; // total round-trip in ms

                if (curl_errno($ch)) {
                    $failedCount++;
                } else {
                    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    if ($statusCode >= 200 && $statusCode < 400) {
                        $successCount++;
                    } else {
                        $failedCount++;
                    }

                    // Extract Server-side Execution Time from response headers
                    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                    $headerStr = substr($response, 0, $headerSize);
                    if (preg_match('/X-Execution-Time:\s*([\d\.]+)ms/i', $headerStr, $matches)) {
                        $serverExecutionTimes[] = (float)$matches[1];
                    }
                    
                    $latencies[] = $duration;
                }
                curl_close($ch);
            }

            if (empty($latencies)) {
                $this->logError("❌ Gagal terhubung ke host untuk pengujian ini.");
                $this->log("----------------------------------------------------");
                continue;
            }

            $minVal = min($latencies);
            $maxVal = max($latencies);
            $avgVal = array_sum($latencies) / count($latencies);

            $minServer = !empty($serverExecutionTimes) ? min($serverExecutionTimes) : 0;
            $maxServer = !empty($serverExecutionTimes) ? max($serverExecutionTimes) : 0;
            $avgServer = !empty($serverExecutionTimes) ? array_sum($serverExecutionTimes) / count($serverExecutionTimes) : 0;

            $this->log("");
            $this->log("📊 \033[1;32mHASIL BENCHMARK:\033[0m");
            $this->log("   Status Permintaan : \033[1;32m$successCount Berhasil\033[0m | \033[1;31m$failedCount Gagal\033[0m");
            $this->log("   ------------------------------------------------");
            $this->log("   \033[1mKategori     | Klien (Round-Trip)  | Server (Aero murni)\033[0m");
            $this->log("   ------------------------------------------------");
            $this->log(sprintf("   Minimum      | %-19s | %-18s", number_format($minVal, 2) . " ms", number_format($minServer, 2) . " ms"));
            $this->log(sprintf("   Maximum      | %-19s | %-18s", number_format($maxVal, 2) . " ms", number_format($maxServer, 2) . " ms"));
            $this->log(sprintf("   Rata-rata    | %-19s | %-18s", number_format($avgVal, 2) . " ms", number_format($avgServer, 2) . " ms"));
            $this->log("   ------------------------------------------------");
            $this->log("");
        }
        $this->log("====================================================");
    }

    protected function createDefaultTests($dir)
    {
        file_put_contents($dir . '/benchmark_users.php', <<<PHP
<?php

return [
    'name' => 'Benchmark GET Users API (Eloquent DB Fetch)',
    'url' => 'http://localhost:8000/users',
    'method' => 'GET',
    'headers' => [
        'Accept' => 'application/json',
    ],
    'requests' => 20,
];
PHP
        );

        file_put_contents($dir . '/benchmark_data.php', <<<PHP
<?php

return [
    'name' => 'Benchmark GET API Data (Public Response)',
    'url' => 'http://localhost:8000/api/data',
    'method' => 'GET',
    'headers' => [
        'Accept' => 'application/json',
    ],
    'requests' => 30,
];
PHP
        );
    }

    protected function logError($message)
    {
        echo "\033[1;31m[ERROR]\033[0m $message\n";
    }

    protected function logWarning($message)
    {
        echo "\033[1;33m[WARN]\033[0m $message\n";
    }

    protected function log($message)
    {
        echo "$message\n";
    }
}
