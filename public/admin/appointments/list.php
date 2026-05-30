<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

require_once(__DIR__ . '/../../../models/Appointment.php');

$appointmentClass = new Appointment();

try {
    $start = $_GET['start'] ?? null;
    $end   = $_GET['end'] ?? null;

    $appointments = $appointmentClass->getAppointments($start, $end);
    echo json_encode($appointments);
} catch (Exception $e) {

    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
