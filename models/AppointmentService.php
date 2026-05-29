<?php

require_once "BaseModel.php";

class AppointmentService extends BaseModel {

    protected $table = "appointment_services";

    public function getServices($appointment_id) {
        return $this->fetchAll("
            SELECT s.*
            FROM appointment_services aps
            INNER JOIN services s
                ON s.id = aps.service_id
            WHERE aps.appointment_id = ?
            ORDER BY s.name ASC
        ", [$appointment_id]);
    }

    public function getServiceIds($appointment_id) {
        $rows = $this->fetchAll("
            SELECT service_id
            FROM appointment_services
            WHERE appointment_id = ?
        ", [$appointment_id]);

        return array_column($rows, 'service_id');
    }

    public function create($data) {
        return $this->execute("
            INSERT INTO appointment_services
            (
                appointment_id,
                service_id
            )
            VALUES (?, ?)
        ", [
            $data['appointment_id'],
            $data['service_id']
        ]);
    }

    public function sync($appointment_id, $service_ids = []) {
        $this->deleteByAppointment($appointment_id);
        foreach ($service_ids as $service_id) {
            $this->create([
                'appointment_id' => $appointment_id,
                'service_id' => $service_id
            ]);
        }

        return true;
    }

    public function deleteByAppointment($appointment_id) {
        return $this->execute(
            "DELETE FROM appointment_services WHERE appointment_id = ?",
            [$appointment_id]
        );
    }

    public function getTotalPrice($appointment_id) {
        $result = $this->fetch("
            SELECT
                SUM(s.price) AS total
            FROM appointment_services aps
            INNER JOIN services s
                ON s.id = aps.service_id
            WHERE aps.appointment_id = ?
        ", [$appointment_id]);

        return $result['total'] ?? 0;
    }

    public function getTotalDuration($appointment_id) {
        $result = $this->fetch("
            SELECT
                SUM(s.duration_minutes) AS duration
            FROM appointment_services aps
            INNER JOIN services s
                ON s.id = aps.service_id
            WHERE aps.appointment_id = ?
        ", [$appointment_id]);

        return $result['duration'] ?? 0;
    }
}
