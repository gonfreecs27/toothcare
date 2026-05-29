<?php

require_once(__DIR__ . '/../../../models/Dentist.php');

header('Content-Type: application/json');

try {
    $dentistClass = new Dentist();
    $dentists = $dentistClass->list();
    echo json_encode($dentists);
} catch (Exception $e) {
    http_response_code(500);

    echo json_encode([
        'error' => 'Failed to fetch dentists'
    ]);
}
