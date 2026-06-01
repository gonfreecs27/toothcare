<?php

require_once "BaseModel.php";

class Feedback extends BaseModel {
    protected $table = "feedbacks";

    protected $fields = [
        'id',
        'name',
        'email',
        'rating',
        'message',
        'status',
        'is_featured',
        'ip_address',
        'created_at',
        'updated_at'
    ];

    public function create($data) {
        $this->execute("
            INSERT INTO feedbacks (
                name,
                email,
                rating,
                message,
                status,
                is_featured,
                ip_address
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ", [
            $data['name'],
            $data['email'] ?? null,
            $data['rating'],
            $data['message'],
            $data['status'] ?? 'pending',
            $data['is_featured'] ?? 0,
            $data['ip_address'] ?? null
        ]);

        return $this->db()->lastInsertId();
    }

    public function update($id, $data) {
        $fields = [];
        $values = [];

        foreach ($data as $key => $value) {
            if (in_array($key, $this->fields) && $key != $this->primaryKey) {
                $fields[] = "{$key} = ?";
                $values[] = $value;
            }
        }

        if (empty($fields)) {
            return false;
        }

        $values[] = $id;

        $this->execute("
            UPDATE {$this->table}
            SET " . implode(', ', $fields) . "
            WHERE id = ?
        ", $values);

        return true;
    }

    public function getApproved() {
        return $this->fetchAll("
            SELECT *
            FROM feedbacks
            WHERE status = 'approved'
            ORDER BY created_at DESC
        ");
    }

    public function getFeatured($limit = 6) {
        return $this->fetchAll("
            SELECT *
            FROM feedbacks
            WHERE status = 'approved'
            AND is_featured = 1
            ORDER BY created_at DESC
            LIMIT {$limit}
        ");
    }

    public function countPending() {
        return $this->fetch("
            SELECT COUNT(*) AS total
            FROM feedbacks
            WHERE status = 'pending'
        ")['total'];
    }

    public function countApproved() {
        return $this->fetch("
            SELECT COUNT(*) AS total
            FROM feedbacks
            WHERE status = 'approved'
        ")['total'];
    }

    public function countRejected() {
        return $this->fetch("
            SELECT COUNT(*) AS total
            FROM feedbacks
            WHERE status = 'rejected'
        ")['total'];
    }

    public function averageRating() {
        return $this->fetch("
            SELECT ROUND(AVG(rating), 1) AS average_rating
            FROM feedbacks
            WHERE status = 'approved'
        ")['average_rating'] ?? 0;
    }

    public function totalApproved() {
        return $this->fetch("
            SELECT COUNT(*) AS total
            FROM feedbacks
            WHERE status = 'approved'
        ")['total'];
    }

    public function allWithLatestFirst() {
        return $this->fetchAll("
            SELECT *
            FROM feedbacks
            ORDER BY created_at DESC
        ");
    }

    public function approve($id) {
        return $this->update($id, [
            'status' => 'approved'
        ]);
    }

    public function reject($id) {
        return $this->update($id, [
            'status' => 'rejected',
            'is_featured' => 0
        ]);
    }

    public function toggleFeatured($id) {
        $feedback = $this->find($id);

        return $this->update($id, [
            'is_featured' => $feedback['is_featured'] ? 0 : 1
        ]);
    }
}
