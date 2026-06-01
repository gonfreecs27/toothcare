<?php

require_once "BaseModel.php";

class Dentist extends BaseModel {
    protected $table = "dentists";
    protected $primaryKey = "id";

    public function all() {
        return $this->fetchAll("
            SELECT 
                d.*,
                u.id  AS user_id,
                u.name
            FROM dentists d
            INNER JOIN users u ON u.id = d.user_id
            ORDER BY d.id DESC
        ");
    }

    public function find($id, $columns = "*") {
        return $this->fetch("
            SELECT 
                d.*,
                u.id AS user_id,
                u.name
            FROM dentists d
            INNER JOIN users u ON u.id = d.user_id
            WHERE d.id = ?
        ", [$id]);
    }

    public function list() {
        return $this->fetchAll("SELECT id, CONCAT(firstname, ' ', lastname) AS name FROM {$this->table} ORDER BY lastname, firstname");
    }

    public function create($data) {
        $db = $this->db();

        try {
            $db->beginTransaction();

            // 1. Create user account
            $stmt = $db->prepare("
                INSERT INTO users (name, email, password, role)
                VALUES (?, ?, ?, 'dentist')
            ");

            $stmt->execute([
                $data['name'],
                $data['email'],
                password_hash($data['password'], PASSWORD_BCRYPT)
            ]);

            $userId = $db->lastInsertId();

            // 2. Create dentist profile
            $stmt = $db->prepare("
                INSERT INTO dentists
                    (user_id, firstname, lastname, specialization, license_number, contact, email, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $userId,
                $data['firstname'],
                $data['lastname'],
                $data['specialization'] ?? null,
                $data['license_number'] ?? null,
                $data['contact'] ?? null,
                $data['email'] ?? null,
                $data['status'] ?? 'active'
            ]);

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public function update($id, $data) {
        $this->execute("
            UPDATE dentists
            SET
                firstname = ?,
                lastname = ?,
                specialization = ?,
                license_number = ?,
                contact = ?,
                status = ?
            WHERE id = ?
        ", [
            $data['firstname'],
            $data['lastname'],
            $data['specialization'] ?? null,
            $data['license_number'] ?? null,
            $data['contact'] ?? null,
            $data['status'] ?? 'active',
            $id
        ]);

        // Update user's name
        $dentist = $this->find($id);

        $name = trim($data['firstname'] . " " . $data['lastname']);
        $this->execute("
            UPDATE users
            SET name = ?
            WHERE id = ?
        ", [
            $name,
            $dentist['user_id']
        ]);
    }

    public function active() {
        return $this->fetchAll("
            SELECT 
                d.id,
                u.name
            FROM dentists d
            INNER JOIN users u ON u.id = d.user_id
            WHERE d.status = 'active'
            ORDER BY u.name ASC
        ");
    }

    public function countActiveDentists() {
        return $this->fetch(
            "SELECT COUNT(*) AS total
         FROM dentists
         WHERE status = 'active'"
        )['total'];
    }
}
