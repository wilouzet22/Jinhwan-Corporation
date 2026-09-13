<?php

class Database {
    private static $instance = null;
    private $connection;

    private $host = '127.0.0.1';
    private $user = 'if0_42216592';
    private $pass = 'IfK0M0n94NKpHQq';
    private $name = 'if0_42216592_jinhwa';

    private function __construct() {
        $this->connection = new mysqli($this->host, $this->user, $this->pass, $this->name);

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }

        $this->connection->set_charset("utf8mb4");
        $this->connection->query("SET NAMES 'utf8mb4'");
        $this->connection->query("SET CHARACTER SET utf8mb4");
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}

