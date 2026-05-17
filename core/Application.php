<?php

namespace Core;

class Application
{
    public static string $ROOT_DIR;
    public Router $router;
    public Request $request;
    public Response $response;
    public Database $db;
    public ?Controller $controller = null;
    public static Application $app;
    protected array $bindings = [];
    protected array $instances = [];

    public function __construct(string $rootPath)
    {
        if (!defined('AERO_START')) {
            define('AERO_START', microtime(true));
        }
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        
        $this->loadEnv();
        $this->setupErrorHandling();

        // Convert SQLite DSN to absolute path if relative
        $dsn = $_ENV['DB_DSN'] ?? '';
        if (strpos($dsn, 'sqlite:') === 0) {
            $dbFile = substr($dsn, 7);
            if ($dbFile !== ':memory:') {
                // If relative path (does not start with a slash and does not have a drive letter like C:)
                if (substr($dbFile, 0, 1) !== '/' && substr($dbFile, 1, 1) !== ':') {
                    $dbFile = $rootPath . '/' . $dbFile;
                }
                
                // Automatically create empty SQLite database file if it doesn't exist
                if (!file_exists($dbFile)) {
                    $dir = dirname($dbFile);
                    if (!is_dir($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    touch($dbFile);
                }
            }
            $_ENV['DB_DSN'] = 'sqlite:' . $dbFile;
        }

        $this->db = new Database([
            'dsn' => $_ENV['DB_DSN'] ?? '',
            'user' => $_ENV['DB_USER'] ?? 'root',
            'password' => $_ENV['DB_PASSWORD'] ?? ''
        ]);

        $this->bootEloquent();
    }

    protected function loadEnv()
    {
        $envPath = self::$ROOT_DIR . '/.env';
        if (file_exists($envPath)) {
            $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);
                if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                    putenv(sprintf('%s=%s', $name, $value));
                    $_ENV[$name] = $value;
                    $_SERVER[$name] = $value;
                }
            }
        }
    }

    public function run()
    {
        $response = $this->router->resolve();
        if (defined('AERO_START')) {
            $ms = number_format((microtime(true) - AERO_START) * 1000, 2);
            header("X-Execution-Time: {$ms}ms");
        }
        echo $response;
    }

    public function getExecutionTime(): float
    {
        return defined('AERO_START') ? (microtime(true) - AERO_START) : 0.0;
    }

    protected function bootEloquent()
    {
        $capsule = new \Illuminate\Database\Capsule\Manager;

        $dsn = $_ENV['DB_DSN'] ?? '';
        
        // Detect driver
        $driver = 'mysql';
        if (strpos($dsn, ':') !== false) {
            $driver = explode(':', $dsn)[0];
        }

        if ($driver === 'sqlite') {
            $databasePath = substr($dsn, 7); // skip 'sqlite:'
            
            $capsule->addConnection([
                'driver'    => 'sqlite',
                'database'  => $databasePath,
                'prefix'    => '',
            ]);
        } elseif ($driver === 'pgsql') {
            $dbConfig = $this->parseDsn($dsn);
            $capsule->addConnection([
                'driver'   => 'pgsql',
                'host'     => $dbConfig['host'] ?? '127.0.0.1',
                'port'     => $dbConfig['port'] ?? '5432',
                'database' => $dbConfig['dbname'] ?? '',
                'username' => $_ENV['DB_USER'] ?? 'postgres',
                'password' => $_ENV['DB_PASSWORD'] ?? '',
                'charset'  => 'utf8',
                'prefix'   => '',
                'schema'   => 'public',
                'sslmode'  => 'prefer',
            ]);
        } else { // default to mysql
            $dbConfig = $this->parseDsn($dsn);
            $capsule->addConnection([
                'driver'    => 'mysql',
                'host'      => $dbConfig['host'] ?? '127.0.0.1',
                'port'      => $dbConfig['port'] ?? '3306',
                'database'  => $dbConfig['dbname'] ?? '',
                'username'  => $_ENV['DB_USER'] ?? 'root',
                'password'  => $_ENV['DB_PASSWORD'] ?? '',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
            ]);
        }

        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }

    protected function parseDsn($dsn)
    {
        $parts = explode(':', $dsn);
        if (count($parts) < 2) return [];
        
        $params = explode(';', $parts[1]);
        $config = [];
        foreach ($params as $param) {
            $kv = explode('=', $param);
            if (count($kv) == 2) {
                $config[$kv[0]] = $kv[1];
            }
        }
        return $config;
    }

    public function bind(string $key, $resolver, bool $singleton = false)
    {
        $this->bindings[$key] = [
            'resolver' => $resolver,
            'singleton' => $singleton
        ];
    }

    public function singleton(string $key, $resolver)
    {
        $this->bind($key, $resolver, true);
    }

    public function make(string $key, array $parameters = [])
    {
        if (isset($this->instances[$key])) {
            return $this->instances[$key];
        }

        if (isset($this->bindings[$key])) {
            $binding = $this->bindings[$key];
            $resolver = $binding['resolver'];
            
            if ($resolver instanceof \Closure) {
                $instance = $resolver($this, $parameters);
            } else {
                $instance = $this->resolve($resolver, $parameters);
            }

            if ($binding['singleton']) {
                $this->instances[$key] = $instance;
            }

            return $instance;
        }

        return $this->resolve($key, $parameters);
    }

    public function resolve(string $className, array $parameters = [])
    {
        if (!class_exists($className)) {
            throw new \Exception("Target class [$className] does not exist.");
        }

        $reflector = new \ReflectionClass($className);

        if (!$reflector->isInstantiable()) {
            throw new \Exception("Target class [$className] is not instantiable.");
        }

        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            return new $className();
        }

        $dependencies = $constructor->getParameters();
        $resolvedDependencies = [];

        foreach ($dependencies as $dependency) {
            $name = $dependency->getName();
            $type = $dependency->getType();

            if (array_key_exists($name, $parameters)) {
                $resolvedDependencies[] = $parameters[$name];
                continue;
            }

            if ($type && !$type->isBuiltin()) {
                $resolvedDependencies[] = $this->make($type->getName());
            } elseif ($dependency->isDefaultValueAvailable()) {
                $resolvedDependencies[] = $dependency->getDefaultValue();
            } else {
                throw new \Exception("Cannot resolve dependency [$name] in class [$className]");
            }
        }

        return $reflector->newInstanceArgs($resolvedDependencies);
    }

    protected function setupErrorHandling()
    {
        error_reporting(E_ALL);

        set_error_handler(function ($severity, $message, $file, $line) {
            if (!(error_reporting() & $severity)) {
                return;
            }
            throw new \ErrorException($message, 0, $severity, $file, $line);
        });

        set_exception_handler(function ($exception) {
            $this->handleException($exception);
        });

        // Tangkap fatal error atau syntax/parse error saat script shutdown
        register_shutdown_function(function () {
            $error = error_get_last();
            if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
                $exception = new \ErrorException(
                    $error['message'],
                    0,
                    $error['type'],
                    $error['file'],
                    $error['line']
                );
                $this->handleException($exception);
            }
        });
    }

    public function handleException($exception)
    {
        $code = $exception instanceof \ErrorException ? 500 : ($exception->getCode() ?: 500);
        if (!is_numeric($code) || $code < 400 || $code > 599) {
            $code = 500;
        }
        
        http_response_code($code);

        $debug = filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN);

        $message = $exception->getMessage();
        $file = $exception->getFile();
        $line = $exception->getLine();
        $trace = $exception->getTraceAsString();

        // Check if API request
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        $path = $_SERVER['REQUEST_URI'] ?? '';
        if (strpos($accept, 'application/json') !== false || strpos($path, '/api/') === 0) {
            header('Content-Type: application/json');
            if ($debug) {
                echo json_encode([
                    'status' => 'error',
                    'code' => $code,
                    'message' => $message,
                    'exception' => get_class($exception),
                    'file' => $file,
                    'line' => $line,
                    'trace' => explode("\n", $trace)
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'code' => $code,
                    'message' => 'Terjadi kesalahan internal server.'
                ]);
            }
            exit;
        }

        // Render HTML view
        if ($debug) {
            include_once self::$ROOT_DIR . '/app/Exceptions/debug_error.php';
        } else {
            $viewFile = self::$ROOT_DIR . "/app/Views/error.php";
            if (file_exists($viewFile)) {
                $message = 'Terjadi kesalahan pada server. Silakan coba beberapa saat lagi.';
                include_once $viewFile;
            } else {
                echo "<h1>Terjadi kesalahan pada server</h1><p>Silakan coba beberapa saat lagi.</p>";
            }
        }
        exit;
    }
}
