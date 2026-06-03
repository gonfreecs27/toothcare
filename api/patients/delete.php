<?php
require '../../init.php';
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
    Core::loadModel("Appointment");
    $patientClass = new Patient();
    $appointmentClass = new Appointment();

    // check if exists first
    $patient = $patientClass->find($id);

    if (!$patient) {
        Response::error('Patient not found', 404);
    }

    if ($appointmentClass->getPatientAppointments($id)) {
        Response::error('Cannot delete patient with existing appointments', 422);
    }

    // Delete
    $patientClass->delete($id);

    Response::success('Patient deleted successfully');
} catch (Exception $e) {
    Response::error('Server error: ' . $e->getMessage(), 500);
}
