<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access'
    ]);
    exit;
}

require_once(__DIR__ . '/../../../models/Patient.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

$id = $_POST['id'] ?? null;

if (!$id) {
    http_response_code(422);
    echo json_encode([
        'success' => false,
        'message' => 'Patient ID is required'
    ]);
    exit;
}

try {
    $patientClass = new Patient();

    // check if exists first
    $patient = $patientClass->find($id);

    if (!$patient) {
        echo json_encode([
            'success' => false,
            'message' => 'Patient not found'
        ]);
        exit;
    }

    // delete
    $patientClass->delete($id);

    echo json_encode([
        'success' => true,
        'message' => 'Patient deleted successfully'
    ]);
} catch (Exception $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}
