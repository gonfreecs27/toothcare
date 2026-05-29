<?php

require_once "BaseModel.php";

class Appointment extends BaseModel {
    protected $table = "appointments";

    public function allWithRelations($filters = []) {
        $sql = "
            SELECT 
                a.*,
                CONCAT(p.firstname, ' ', p.lastname) AS patient_name,
                CONCAT(u.name) AS dentist_name
            FROM appointments a
            INNER JOIN patients p ON p.id = a.patient_id
            INNER JOIN dentists d ON d.id = a.dentist_id
            INNER JOIN users u ON u.id = d.user_id
            WHERE 1=1
        ";

        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND a.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['dentist_id'])) {
            $sql .= " AND a.dentist_id = ?";
            $params[] = $filters['dentist_id'];
        }

        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(a.appointment_start) >= ?";
            $params[] = $filters['date_from'];
        }

        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(a.appointment_start) <= ?";
            $params[] = $filters['date_to'];
        }

        $sql .= " ORDER BY a.appointment_start DESC";

        return $this->fetchAll($sql, $params);
    }

    public function create($data) {
        return $this->execute("
            INSERT INTO appointments
                (patient_id, dentist_id, appointment_start, appointment_end, status, reason)
            VALUES (?, ?, ?, ?, ?, ?)
        ", [
            $data['patient_id'],
            $data['dentist_id'],
            $data['appointment_start'],
            $data['appointment_end'],
            $data['status'] ?? 'pending',
            $data['reason'] ?? null
        ]);
    }

    public function update($id, $data) {
        return $this->execute("
            UPDATE appointments
            SET patient_id = ?, dentist_id = ?, appointment_start = ?, appointment_end = ?, status = ?, reason = ?
            WHERE id = ?
        ", [
            $data['patient_id'],
            $data['dentist_id'],
            $data['appointment_start'],
            $data['appointment_end'],
            $data['status'],
            $data['reason'],
            $id
        ]);
    }

    public function findConflict($dentist_id, $start_datetime, $end_datetime, $exclude_id = null) {

        $sql = "SELECT id FROM appointments
            WHERE dentist_id = ? AND status != 'cancelled'
            AND appointment_start < ? AND appointment_end > ?";

        $params = [
            $dentist_id,
            $end_datetime,
            $start_datetime
        ];

        if ($exclude_id) {
            $sql .= " AND id != ?";
            $params[] = $exclude_id;
        }

        $sql .= " LIMIT 1";

        $result = $this->fetch(
            $sql,
            $params
        );

        return $result;
    }
}
