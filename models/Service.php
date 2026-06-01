<?php

require_once "BaseModel.php";

class Service extends BaseModel {

    protected $table = "services";

    public function paginate($page, $limit, $search = '', $duration = null, $sort = 'name') {
        $offset = ($page - 1) * $limit;

        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND name LIKE ?";
            $params[] = "%$search%";
        }

        if (!empty($duration)) {
            $sql .= " AND duration_minutes = ?";
            $params[] = $duration;
        }

        $allowedSort = [
            'name' => 'name ASC',
            'price' => 'price ASC',
            'duration' => 'duration_minutes ASC'
        ];

        $orderBy = $allowedSort[$sort] ?? 'name ASC';

        $sql .= " ORDER BY $orderBy";

        $countSql = "SELECT COUNT(*) as total FROM ($sql) as t";
        $total = $this->fetch($countSql, $params)['total'] ?? 0;

        $sql .= " LIMIT $limit OFFSET $offset";

        $data = $this->fetchAll($sql, $params);

        return [
            'data' => $data,
            'pagination' => [
                'page' => $page,
                'pages' => ceil($total / $limit),
                'total' => $total
            ]
        ];
    }

    public function create($data) {
        $this->execute("
            INSERT INTO services
            (
                name,
                description,
                price,
                duration_minutes
            )
            VALUES (?, ?, ?, ?)
        ", [
            $data['name'],
            $data['description'] ?? null,
            $data['price'] ?? 0,
            $data['duration_minutes'] ?? 30
        ]);

        return $this->db()->lastInsertId();
    }

    public function update($id, $data) {
        return $this->execute("
            UPDATE services
            SET
                name = ?,
                description = ?,
                price = ?,
                duration_minutes = ?
            WHERE id = ?
        ", [
            $data['name'],
            $data['description'] ?? null,
            $data['price'] ?? 0,
            $data['duration_minutes'] ?? 30,
            $id
        ]);
    }

    public function getByIds($ids) {
        if (empty($ids)) return [];
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        return $this->fetchAll("SELECT * FROM services WHERE id IN ($placeholders)", $ids);
    }

    public function totalServices() {
        return $this->fetch("
            SELECT COUNT(*) AS total
            FROM services
        ")['total'];
    }
}
