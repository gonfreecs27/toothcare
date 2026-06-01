<?php

require_once "BaseModel.php";

class Patient extends BaseModel {
    protected $table = 'patients';

    public function list() {
        return $this->fetchAll("SELECT id, CONCAT(firstname, ' ', lastname) AS name FROM {$this->table} ORDER BY lastname, firstname");
    }

    public function create($data) {
        $this->execute(
            "INSERT INTO {$this->table}
             (firstname, lastname, birthdate, gender, contact, email, address, civil_status, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['firstname'],
                $data['lastname'],
                $data['birthdate'],
                $data['gender'],
                $data['contact'],
                $data['email'],
                $data['address'],
                $data['civil_status'],
                $data['status']
            ]
        );

        return $this->db()->lastInsertId();
    }

    public function update($id, $data) {
        return $this->execute(
            "UPDATE {$this->table}
             SET firstname = ?, lastname = ?, birthdate = ?, gender = ?, contact = ?, email = ?, address = ?, civil_status = ?, status = ?
             WHERE id = ?",
            [
                $data['firstname'],
                $data['lastname'],
                $data['birthdate'],
                $data['gender'],
                $data['contact'],
                $data['email'],
                $data['address'],
                $data['civil_status'],
                $data['status'],
                $id
            ]
        );
    }

    public function findByEmail($email) {
        return $this->fetch(
            "SELECT id FROM {$this->table}
         WHERE email = ?
         LIMIT 1",
            [$email]
        );
    }

    public function countPatients() {
        return $this->fetch(
            "SELECT COUNT(*) AS total FROM patients"
        )['total'];
    }
}
