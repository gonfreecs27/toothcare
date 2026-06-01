<?php

require_once "BaseModel.php";

class Payment extends BaseModel {

    protected $table = "payments";

    protected $fields = [
        'id',
        'appointment_id',
        'amount',
        'payment_method',
        'reference_no',
        'paid_at',
        'created_at'
    ];

    public function generateReferenceNo() {
        $prefix = "PAY";

        $date = date('Ymd');

        // get last ID for today
        $row = $this->fetch("
            SELECT COUNT(id) AS total
            FROM payments
            WHERE DATE(created_at) = CURDATE()
        ");

        $sequence = str_pad(($row['total'] ?? 0) + 1, 6, "0", STR_PAD_LEFT);

        return "{$prefix}-{$date}-{$sequence}";
    }

    public function createFromAppointment($appointmentId, $data = []) {
        // check existing payment
        $existing = $this->fetch("
            SELECT id FROM payments
            WHERE appointment_id = ?
            LIMIT 1
        ", [$appointmentId]);

        if ($existing) {
            return $existing['id'];
        }

        $amount = $this->calculateAppointmentAmount($appointmentId);

        $referenceNo = $this->generateReferenceNo();

        $this->execute("
            INSERT INTO payments
                (appointment_id, amount, payment_method, reference_no, paid_at)
            VALUES (?, ?, ?, ?, ?)
        ", [
            $appointmentId,
            $amount,
            $data['payment_method'] ?? 'cash',
            $referenceNo,
            date('Y-m-d H:i:s')
        ]);

        return $this->db()->lastInsertId();
    }

    public function findByAppointment($appointmentId) {
        return $this->fetch("
            SELECT * FROM payments
            WHERE appointment_id = ?
            LIMIT 1
        ", [$appointmentId]);
    }

    public function calculateAppointmentAmount($appointmentId) {
        $row = $this->fetch("
            SELECT SUM(s.price) AS total
            FROM appointment_services aps
            JOIN services s ON s.id = aps.service_id
            WHERE aps.appointment_id = ?
        ", [$appointmentId]);

        return (float) ($row['total'] ?? 0);
    }

    public function updateAmountByAppointment($appointmentId, $amount) {
        return $this->execute("
            UPDATE payments
            SET amount = ?
            WHERE appointment_id = ?
        ", [
            $amount,
            $appointmentId
        ]);
    }

    public function getPaymentsWithDetails() {
        return $this->fetchAll("
            SELECT 
                p.*,
                a.status AS appointment_status,
                a.payment_status,
                CONCAT(pa.firstname, ' ', pa.lastname) AS patient_name,
                CONCAT(d.firstname, ' ', d.lastname) AS dentist_name,
                a.appointment_start
            FROM payments p
            JOIN appointments a ON a.id = p.appointment_id
            JOIN patients pa ON pa.id = a.patient_id
            JOIN dentists d ON d.id = a.dentist_id
            ORDER BY p.paid_at DESC
        ");
    }

    // =========================
    // STATS
    // =========================
    public function totalRevenue() {
        return $this->fetch("
            SELECT COALESCE(SUM(amount),0) AS total
            FROM payments
        ")['total'];
    }

    public function todayRevenue() {
        return $this->fetch("
            SELECT COALESCE(SUM(amount),0) AS total
            FROM payments
            WHERE DATE(paid_at) = CURDATE()
        ")['total'];
    }

    public function monthlyRevenue() {
        return $this->fetch("
            SELECT COALESCE(SUM(amount),0) AS total
            FROM payments
            WHERE MONTH(paid_at) = MONTH(CURDATE())
            AND YEAR(paid_at) = YEAR(CURDATE())
        ")['total'];
    }

    public function countPayments() {
        return $this->fetch("
            SELECT COUNT(*) AS total FROM payments
        ")['total'];
    }
}
