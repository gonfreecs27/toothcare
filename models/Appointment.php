<?php

require_once "BaseModel.php";
require_once "AppointmentService.php";

class Appointment extends BaseModel {
    protected $table = "appointments";

    protected $fields = [
        'id',
        'patient_id',
        'dentist_id',
        'appointment_start',
        'appointment_end',
        'status',
        'payment_status',
        'confirmed_at',
        'completed_at',
        'cancelled_at',
        'reason',
        'created_at',
        'updated_at'
    ];

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

        $sql = "
            UPDATE {$this->table}
            SET " . implode(', ', $fields) . "
            WHERE id = ?
        ";

        $this->execute($sql, $values);

        if (isset($data['services'])) {
            $appointmentService = new AppointmentService();
            $appointmentService->sync($id, $data['services']);
        }

        return true;
    }

    public function updateSchedule($id, $start, $end) {
        return $this->execute("
            UPDATE appointments
            SET
                appointment_start = ?,
                appointment_end = ?
            WHERE id = ?
        ", [
            $start,
            $end,
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

    public function totalAppointments() {
        return $this->fetch("
            SELECT COUNT(*) AS total
            FROM appointments
        ")['total'];
    }

    public function appointmentsThisMonth() {
        return $this->fetch("
            SELECT COUNT(*) AS total
            FROM appointments
            WHERE YEAR(appointment_start)=YEAR(CURDATE())
            AND MONTH(appointment_start)=MONTH(CURDATE())
        ")['total'];
    }

    public function countToday() {
        return $this->fetch("
            SELECT COUNT(*) AS total
            FROM appointments
            WHERE DATE(appointment_start) = CURDATE()
        ")['total'];
    }

    public function countPending() {
        return $this->fetch("
            SELECT COUNT(*) AS total
            FROM appointments
            WHERE status = 'pending'
        ")['total'];
    }

    public function countConfirmed() {
        return $this->fetch("
            SELECT COUNT(*) AS total
            FROM appointments
            WHERE status = 'confirmed'
        ")['total'];
    }

    public function countCompleted() {
        return $this->fetch("
            SELECT COUNT(*) AS total
            FROM appointments
            WHERE status = 'completed'
        ")['total'];
    }

    public function countCancelled() {
        return $this->fetch("
            SELECT COUNT(*) AS total
            FROM appointments
            WHERE status = 'cancelled'
        ")['total'];
    }

    public function todaySchedule($limit = 10) {
        return $this->fetchAll("
            SELECT
                a.*,
                CONCAT(p.firstname,' ',p.lastname) AS patient_name,
                CONCAT(d.firstname,' ',d.lastname) AS dentist_name
            FROM appointments a
            JOIN patients p ON p.id = a.patient_id
            JOIN dentists d ON d.id = a.dentist_id
            WHERE DATE(a.appointment_start) = CURDATE()
            ORDER BY a.appointment_start ASC
            LIMIT {$limit}
        ");
    }
}
