<?php

require_once "BaseModel.php";

class User extends BaseModel {
    protected $table = 'users';

    public function login($email) {
        return $this->fetch(
            "SELECT * FROM {$this->table} WHERE email = ?",
            [$email]
        );
    }

    public function create($data) {
        return $this->execute(
            "INSERT INTO {$this->table} (name, email, password, role)
             VALUES (?, ?, ?, ?)",
            [
                $data['name'],
                $data['email'],
                password_hash($data['password'], PASSWORD_BCRYPT),
                $data['role'] ?? 'staff'
            ]
        );
    }

    public function update($id, $data) {
        $db = $this->conn;

        try {
            $db->beginTransaction();

            // -------------------------
            // 1. Update USER
            // -------------------------
            if (!empty($data['password'])) {
                $stmt = $db->prepare("
                    UPDATE {$this->table}
                    SET name = ?, email = ?, password = ?, role = ?
                    WHERE id = ?
                ");

                $stmt->execute([
                    $data['name'],
                    $data['email'],
                    password_hash($data['password'], PASSWORD_BCRYPT),
                    $data['role'],
                    $id
                ]);
            } else {
                $stmt = $db->prepare("
                    UPDATE {$this->table}
                    SET name = ?, email = ?, role = ?
                    WHERE id = ?
                ");

                $stmt->execute([
                    $data['name'],
                    $data['email'],
                    $data['role'],
                    $id
                ]);
            }

            // -------------------------
            // 2. STRICT DENTIST VALIDATION
            // -------------------------
            if ($data['role'] === 'dentist') {
                $stmt = $db->prepare("SELECT id FROM dentists WHERE user_id = ?");
                $stmt->execute([$id]);
                $exists = $stmt->fetch();
                if (!$exists) {
                    throw new Exception("Cannot assign role 'dentist'. Dentist profile does not exist.");
                }
            }

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public function verifyPassword($email, $password) {
        $user = $this->login($email);

        if (!$user) return false;

        if (password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }

        return false;
    }
}
