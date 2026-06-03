<?php
require '../../init.php';
Permission::authorize(['all']);

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

    Response::success('Appointments retrieved successfully', [
        'events' => $appointments,
        'tally' => $tally
    ]);
} catch (Exception $e) {
    Response::error($e->getMessage(), 500);
}
