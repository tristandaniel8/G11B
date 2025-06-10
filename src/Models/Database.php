<?php

class Database {
    private ?PDO $pdo = null;

    public function __construct(
        private string $host,
        private string $name,
        private string $user,
        private string $pass
    ) {}

    public function getConnection(): PDO {
        if ($this->pdo === null) {
            $dsn = "mysql:host={$this->host};dbname={$this->name};charset=utf8mb4";
            $this->pdo = new PDO($dsn, $this->user, $this->pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }
        return $this->pdo;
    }
}