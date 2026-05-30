<?php
require '../../../init.php';
header('Content-Type: application/json');

if (!Permission::hasAccess(['all'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

Core::loadModel("Appointment");
$appointmentClass = new Appointment();

try {
    $start = $_POST['start'] ?? null;
    $end   = $_POST['end'] ?? null;

    $appointments = $appointmentClass->getAppointments($start, $end);
    $tally = ["totalAppointments" => count($appointments)];

    foreach ($appointments as $a) {
        $key = strtolower($a['extendedProps']['status']) . 'Appointments';
        $tally[$key] = ($tally[$key] ?? 0) + 1;
    }

    echo json_encode([
        "events" => $appointments,
        "tally" => $tally
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
