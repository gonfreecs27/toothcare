<?php

require_once "BaseModel.php";

class PasswordReset extends BaseModel {
    protected $table = 'password_resets';

    public function create($userId, $token, $expiresAt) {
        return $this->execute(
            "INSERT INTO {$this->table}
            (user_id, token, expires_at)
            VALUES (?, ?, ?)",
            [
                $userId,
                $token,
                $expiresAt
            ]
        );
    }

    public function findValidToken($token) {
        return $this->fetch(
            "SELECT *
            FROM {$this->table}
            WHERE token = ?
            AND expires_at > NOW()
            LIMIT 1",
            [$token]
        );
    }

    public function deleteByUser($userId) {
        return $this->execute(
            "DELETE FROM {$this->table}
            WHERE user_id = ?",
            [$userId]
        );
    }

    public function deleteExpired() {
        return $this->execute(
            "DELETE FROM {$this->table}
            WHERE expires_at <= NOW()"
        );
    }

    public function createToken($userId) {
        $token = bin2hex(random_bytes(32));

        $this->deleteByUser($userId);

        $this->create(
            $userId,
            $token,
            date('Y-m-d H:i:s', strtotime('+1 hour'))
        );

        return $token;
    }

    public function verifyToken($token) {
        return $this->findValidToken($token);
    }
}
