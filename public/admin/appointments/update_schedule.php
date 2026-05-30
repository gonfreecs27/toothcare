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
    $start = trim($_POST['start'] ?? '');
    $end = trim($_POST['end'] ?? '');

    if (!$id) {
        throw new Exception('Appointment ID is required');
    }

    if (!$start || !$end) {
        throw new Exception('Start and end datetime are required');
    }

    $appointment = $appointmentClass->find($id);

    if (!$appointment) {
        throw new Exception('Appointment not found');
    }

    if (strtotime($end) <= strtotime($start)) {
        throw new Exception('End time must be greater than start time');
    }

    $conflict = $appointmentClass->findConflict(
        $appointment['dentist_id'],
        $start,
        $end,
        $id
    );

    if ($conflict) {
        throw new Exception('Dentist already has an appointment at this time');
    }

    $updated = $appointmentClass->updateSchedule(
        $id,
        $start,
        $end
    );

    if (!$updated) {
        throw new Exception('Failed to update appointment schedule');
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
