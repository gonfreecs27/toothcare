<?php
require '../../../init.php';
Permission::authorize(['admin', 'staff', 'dentist']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Response::error('Invalid request method', 405);
}

$id = $_POST['id'] ?? null;

if (!$id) {
    Response::error('Patient ID is required', 422);
}

try {
    Core::loadModel("Patient");
    $patientClass = new Patient();

    // check if exists first
    $patient = $patientClass->find($id);

    if (!$patient) {
        Response::error('Patient not found', 404);
    }

    // delete
    $patientClass->delete($id);

    Response::success('Patient deleted successfully');
} catch (Exception $e) {
    Response::error('Server error: ' . $e->getMessage(), 500);
}
