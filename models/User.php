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

    public function findByEmail($email) {
        return $this->fetch("
            SELECT *
            FROM users
            WHERE email = ?
            LIMIT 1
        ", [$email]);
    }

    public function validatePassword($password) {
        $password = trim($password);

        // ---------------------------------
        // 1. Minimum length check
        // ---------------------------------
        if (strlen($password) < 8) {
            return [
                'valid' => false,
                'message' => 'Password must be at least 8 characters long.'
            ];
        }

        // ---------------------------------
        // 2. Maximum length (prevent abuse)
        // ---------------------------------
        if (strlen($password) > 72) {
            return [
                'valid' => false,
                'message' => 'Password is too long.'
            ];
        }

        // ---------------------------------
        // 3. Must contain at least 1 lowercase
        // ---------------------------------
        if (!preg_match('/[a-z]/', $password)) {
            return [
                'valid' => false,
                'message' => 'Password must contain at least one lowercase letter.'
            ];
        }

        // ---------------------------------
        // 4. Must contain at least 1 uppercase
        // ---------------------------------
        if (!preg_match('/[A-Z]/', $password)) {
            return [
                'valid' => false,
                'message' => 'Password must contain at least one uppercase letter.'
            ];
        }

        // ---------------------------------
        // 5. Must contain at least 1 number
        // ---------------------------------
        if (!preg_match('/[0-9]/', $password)) {
            return [
                'valid' => false,
                'message' => 'Password must contain at least one number.'
            ];
        }

        // ---------------------------------
        // 6. Must contain at least 1 special character
        // ---------------------------------
        if (!preg_match('/[\W_]/', $password)) {
            return [
                'valid' => false,
                'message' => 'Password must contain at least one special character.'
            ];
        }

        // ---------------------------------
        // 7. Block common weak passwords
        // ---------------------------------
        $common = [
            'password',
            '12345678',
            'qwerty',
            'admin123',
            'password123'
        ];

        if (in_array(strtolower($password), $common)) {
            return [
                'valid' => false,
                'message' => 'Password is too common. Choose a stronger one.'
            ];
        }

        // ---------------------------------
        // Passed all checks
        // ---------------------------------
        return [
            'valid' => true,
            'message' => 'Password is strong.'
        ];
    }

    public function updatePassword($id, $password) {
        return $this->execute(
            "UPDATE {$this->table}
            SET password = ?
            WHERE id = ?",
            [
                password_hash($password, PASSWORD_BCRYPT),
                $id
            ]
        );
    }
}
