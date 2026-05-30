<?php
require '../../../init.php';
header('Content-Type: application/json');

try {
    $patientClass = new Patient();
    $patients = $patientClass->list();
    echo json_encode($patients);
} catch (Exception $e) {
    http_response_code(500);

    echo json_encode([
        'error' => 'Failed to fetch patients'
    ]);
}
