<?php

session_start();

require_once(__DIR__ . '/../../../models/Appointment.php');

$appointmentModel = new Appointment();

$id = $_POST['id'] ?? null;
$date = $_POST['appointment_date'] ?? null;

if (!$id || !$date) {
    http_response_code(400);
    exit('Invalid request');
}

try {

    $appointmentModel->update($id, [
        'patient_id' => $_POST['patient_id'] ?? null,
        'dentist_id' => $_POST['dentist_id'] ?? null,
        'appointment_date' => $date,
        'status' => $_POST['status'] ?? 'pending',
        'reason' => $_POST['reason'] ?? null
    ]);

    echo "OK";
} catch (Exception $e) {
    http_response_code(500);
    echo $e->getMessage();
}
