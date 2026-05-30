<?php
require '../../../init.php';
header('Content-Type: application/json');

if (!Permission::hasAccess(['admin', 'staff'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

Core::loadModel("Appointment");
$appointmentClass = new Appointment();

try {

    $id = (int) ($_POST['id'] ?? 0);

    $patient_id = trim($_POST['patient_id'] ?? '');
    $dentist_id = trim($_POST['dentist_id'] ?? '');
    $date = trim($_POST['appointment_date'] ?? '');
    $start_time = trim($_POST['start_time'] ?? '');
    $end_time = trim($_POST['end_time'] ?? '');
    $status = trim($_POST['status'] ?? 'pending');
    $reason = trim($_POST['reason'] ?? '');

    $services = $_POST['services'] ?? [];

    // convert to integers & remove invalid values
    $services = array_values(array_filter(array_map('intval', $services)));

    if (!$id) {
        throw new Exception('Appointment ID is required');
    }

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

    $conflict = $appointmentClass->findConflict(
        $dentist_id,
        $start,
        $end,
        $id
    );

    if ($conflict) {
        throw new Exception('Dentist already has an appointment at this time');
    }

    $updated = $appointmentClass->update($id, [
        'patient_id' => $patient_id,
        'dentist_id' => $dentist_id,
        'appointment_start' => $start,
        'appointment_end' => $end,
        'status' => $status,
        'reason' => $reason,
        'services' => $services
    ]);

    if (!$updated) {
        throw new Exception('Failed to update appointment');
    }

    echo json_encode([
        'success' => true,
        'message' => 'Appointment updated successfully'
    ]);
} catch (Exception $e) {
    http_response_code(422);
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
