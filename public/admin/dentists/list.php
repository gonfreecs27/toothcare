<?php
require '../../../init.php';
header('Content-Type: application/json');

try {
    Core::loadModel("Dentist");
    $dentistClass = new Dentist();
    $dentists = $dentistClass->list();
    echo json_encode($dentists);
} catch (Exception $e) {
    http_response_code(500);

    echo json_encode([
        'error' => 'Failed to fetch dentists'
    ]);
}
