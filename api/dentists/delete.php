<?php
require '../../init.php';
Permission::authorize(['admin']);

$id = $_POST['id'] ?? null;

if (!$id) {
    Response::error('Dentist ID is required', 422);
}

try {
    Core::loadModel("Dentist");
    Core::loadModel("Appointment");
    $dentistClass = new Dentist();
    $appointmentClass = new Appointment();

    $dentist = $dentistClass->find($id);

    if (!$dentist) {
        Response::error('Dentist not found', 404);
    }

    if ($dentist['user_id'] == $_SESSION['user']['id']) {
        Response::error('You cannot delete your own account', 422);
    }

    if ($appointmentClass->getDentistAppointments($id)) {
        Response::error('Cannot delete dentist with existing appointments', 422);
    }

    $deleted = $dentistClass->delete($id);

    // Delete user too
    Core::loadModel("User");
    $userClass = new User();
    $userClass->delete($dentist['user_id']);

    Response::success('Dentist deleted successfully');
} catch (PDOException $e) {
    Response::error('Failed to delete dentist', 500);
}
