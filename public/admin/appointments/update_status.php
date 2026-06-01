<?php
require '../../../init.php';
Permission::authorize(['admin', 'staff']);

try {
    Core::loadModel("Appointment");
    $appointmentClass = new Appointment();

    $id = $_POST['id'] ?? null;
    $status = $_POST['status'] ?? null;
    $paymentStatus = $_POST['payment_status'] ?? null;

    if (!$id || !$status) {
        Response::error('Missing required fields', 422);
    }

    $allowedStatuses = ['pending', 'confirmed', 'cancelled', 'completed'];

    if (!in_array($status, $allowedStatuses)) {
        Response::error('Invalid status', 422);
    }

    $appointment = $appointmentClass->find($id);

    if (!$appointment) {
        Response::error('Appointment not found', 404);
    }

    // =========================
    // BUSINESS RULES (STATE MACHINE)
    // =========================
    $currentStatus = $appointment['status'];

    $allowedTransitions = [
        'pending' => ['confirmed', 'cancelled'],
        'confirmed' => ['completed', 'cancelled'],
        'cancelled' => ['pending'],
        'completed' => ['completed']
    ];

    if (!in_array($status, $allowedTransitions[$currentStatus])) {
        Response::error("Cannot change status from {$currentStatus} to {$status}", 403);
    }

    // =========================
    // UPDATE STATUS
    // =========================
    $updateData = [
        'status' => $status,
        'updated_at' => date('Y-m-d H:i:s')
    ];

    if ($paymentStatus) {
        $updateData['payment_status'] = $paymentStatus;
    }

    $now = date('Y-m-d H:i:s');
    switch ($status) {
        case 'completed':
            $updateData['completed_at'] = $now;
            break;

        case 'confirmed':
            $updateData['confirmed_at'] = $now;
            break;

        case 'cancelled':
            $updateData['cancelled_at'] = $now;
            break;
    }

    $appointmentClass->update($id, $updateData);

    // Need to add also the payment transaction

    Response::success('Appointment status updated successfully');
} catch (Exception $e) {
    Response::error('Server error', 500);
}
