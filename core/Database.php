<?php

namespace Core;

class Database
{
    private ?\PDO $_pdo = null;
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function __get($name)
    {
        if ($name === 'pdo') {
            if ($this->_pdo === null) {
                $this->connect();
            }
            return $this->_pdo;
        }
        return null;
    }

    private function connect()
    {
        $dsn = $this->config['dsn'] ?? '';
        $user = $this->config['user'] ?? '';
        $password = $this->config['password'] ?? '';

        if (empty($dsn)) {
            throw new \Exception("Database DSN belum disetting di file .env");
        }

        try {
            $this->_pdo = new \PDO($dsn, $user, $password);
            $this->_pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw new \Exception("Koneksi Database Gagal: " . $e->getMessage());
        }
    }
}
