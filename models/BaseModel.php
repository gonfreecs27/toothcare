<?php

require_once "Database.php";

class BaseModel extends Database {
    protected $table;
    protected $primaryKey = 'id';

    public function all() {
        return $this->fetchAll("
            SELECT *
            FROM {$this->table}
        ");
    }

    public function find($id, $columns = "*") {
        return $this->fetch("
            SELECT {$columns}
            FROM {$this->table}
            WHERE {$this->primaryKey} = ?
        ", [$id]);
    }

    public function delete($id) {
        return $this->execute("
            DELETE FROM {$this->table}
            WHERE {$this->primaryKey} = ?
        ", [$id]);
    }

    public function count() {
        return $this->fetch("
            SELECT COUNT(*) AS c
            FROM {$this->table}
        ")['c'];
    }
}
