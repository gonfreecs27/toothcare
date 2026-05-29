<?php

require_once(__DIR__ . '/../../../models/Appointment.php');

header('Content-Type: application/json');

$appointmentModel = new Appointment();

$data = $appointmentModel->allWithRelations();

$events = [];

foreach ($data as $a) {

    $color =
        $a['status'] === 'completed' ? '#28a745' : ($a['status'] === 'cancelled' ? '#dc3545' : ($a['status'] === 'confirmed' ? '#0d6efd' : '#ffc107'));

    $events[] = [
        'id' => $a['id'],
        'title' => $a['patient_name'] . ' - ' . $a['dentist_name'],
        'start' => $a['appointment_date'],
        'color' => $color
    ];
}

echo json_encode($events);
