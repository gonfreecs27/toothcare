<?php

require_once "BaseModel.php";
require_once "AppointmentService.php";

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

    public function getAppointments($start = null, $end = null) {
        $params = [];
        $where = "";
        
        if ($start && $end) {
            $start = date('Y-m-d H:i:s', strtotime($start));
            $end = date('Y-m-d H:i:s', strtotime($end));

            $where = "
                WHERE a.appointment_start < ?
                AND a.appointment_end > ?
            ";

            $params[] = $end;
            $params[] = $start;
        }

        $sql = "
            SELECT 
                a.*,
                CONCAT(p.firstname, ' ', p.lastname) AS patient_name,
                CONCAT(d.firstname, ' ', d.lastname) AS dentist_name
            FROM appointments a
            JOIN patients p ON p.id = a.patient_id
            JOIN dentists d ON d.id = a.dentist_id
            $where
            ORDER BY a.appointment_start DESC
        ";

        $appointments = $this->fetchAll($sql, $params);
        $events = [];

        foreach ($appointments as $row) {
            $services = $this->fetchAll("
                SELECT s.id, s.name, s.price
                FROM appointment_services aps
                JOIN services s ON s.id = aps.service_id
                WHERE aps.appointment_id = ?
            ", [$row['id']]);

            $row['services'] = $services;

            $events[] = [
                'id' => $row['id'],
                'title' => $row['patient_name'],
                'start' => $row['appointment_start'],
                'end' => $row['appointment_end'],

                'extendedProps' => $row
            ];
        }

        return $events;
    }

    public function create($data) {
        $this->execute("
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

        $appointmentId = $this->db()->lastInsertId();

        $appointmentService = new AppointmentService();
        $appointmentService->sync($appointmentId, $data['services'] ?? []);

        return $appointmentId;
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
