<?php

class Database {
    protected PDO $conn;

    public function __construct() {
        $this->conn = new PDO(
            'mysql:host=localhost;dbname=toothcare;charset=utf8',
            'root',
            '',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    }

    public function db(): PDO {
        return $this->conn;
    }

    protected function fetchAll($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    protected function fetch($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    protected function execute($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }
}
