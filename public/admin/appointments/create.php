<?php
require '../../../init.php';
Permission::authorize(['admin', 'staff']);

Core::loadModel("Appointment");
Core::loadModel("Payment");

$appointmentClass = new Appointment();
$paymentClass = new Payment();

try {
    $patient_id = trim($_POST['patient_id'] ?? '');
    $dentist_id = trim($_POST['dentist_id'] ?? '');
    $date = trim($_POST['appointment_date'] ?? '');
    $start_time = trim($_POST['start_time'] ?? '');
    $end_time = trim($_POST['end_time'] ?? '');
    $status = trim($_POST['status'] ?? 'pending');
    $reason = trim($_POST['reason'] ?? '');
    $services = $_POST['services'] ?? [];

    $services = array_values(array_filter(array_map('intval', $services)));

    if (!$patient_id) {
        throw new Exception('Patient is required');
    }

    if (!$dentist_id) {
        throw new Exception('Dentist is required');
    }

    if (!$date) {
        throw new Exception('Date is required');
    }

    if (!$start_time) {
        throw new Exception('Start time is required');
    }

    if (!$end_time) {
        throw new Exception('End time is required');
    }

    if (empty($services)) {
        throw new Exception('Select a service.');
    }

    $startDateTime = DateTime::createFromFormat('Y-m-d H:i', "$date $start_time");
    $endDateTime   = DateTime::createFromFormat('Y-m-d H:i', "$date $end_time");

    if (!$startDateTime || !$endDateTime) {
        throw new Exception('Invalid date or time format');
    }

    if ($endDateTime <= $startDateTime) {
        throw new Exception('End time must be greater than start time');
    }

    $start = $startDateTime->format('Y-m-d H:i:s');
    $end   = $endDateTime->format('Y-m-d H:i:s');

    $existing = $appointmentClass->findConflict($dentist_id, $start, $end);

    if ($existing) {
        throw new Exception('Dentist already has an appointment at this time');
    }

    $appointmentId = $appointmentClass->create([
        'patient_id' => $patient_id,
        'dentist_id' => $dentist_id,
        'appointment_start' => $start,
        'appointment_end' => $end,
        'status' => $status,
        'reason' => $reason,
        'services' => $services
    ]);

    if (!$appointmentId) {
        throw new Exception('Failed to save appointment');
    }

    $paymentClass->createFromAppointment($appointmentId, [
        'payment_method' => 'cash',
        'reference_no' => $paymentClass->generateReferenceNo()
    ]);

    Response::success('Appointment created successfully');
} catch (Exception $e) {
    Response::error($e->getMessage(), 422);
}
