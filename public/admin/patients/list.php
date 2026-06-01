<?php
require '../../../init.php';

try {
    Core::loadModel("Patient");
    $patientClass = new Patient();
    $patients = $patientClass->list();
    Response::success('Patients retrieved successfully', $patients);
} catch (Exception $e) {
    Response::error('Failed to fetch patients', 500);
}
