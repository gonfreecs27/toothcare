<?php
require '../../init.php';
Permission::authorize(['admin', 'staff', 'dentist']);

try {
    Core::loadModel("Appointment");
    $appointmentClass = new Appointment();

    $id = $_POST['id'] ?? null;
    $status = $_POST['status'] ?? null;
    $paymentStatus = $_POST['payment_status'] ?? null;

    if (!$id || !$status) {
        Response::error('Missing required fields', 422);
    }

    $appointment = $appointmentClass->find($id);

    if (!$appointment) {
        Response::error('Appointment not found', 404);
    }

    // =========================
    // STATE MACHINE
    // =========================
    $currentStatus = $appointment['status'];

    $allowedTransitions = [
        'pending' => ['confirmed', 'cancelled'],
        'confirmed' => ['confirmed', 'completed', 'cancelled'],
        'cancelled' => ['pending'],
        'completed' => ['completed']
    ];

    if (
        !isset($allowedTransitions[$currentStatus]) ||
        !in_array($status, $allowedTransitions[$currentStatus])
    ) {
        Response::error("Cannot change status from {$currentStatus} to {$status}", 403);
    }

    // =========================
    // PAYMENT HANDLING
    // =========================
    if ($paymentStatus === 'paid') {
        $appointmentClass->markAsPaid($id, [
            'payment_method' => $_POST['payment_method'] ?? 'cash',
            'reference_no' => $_POST['reference_no'] ?? null
        ]);
    }

    // =========================
    // UPDATE DATA
    // =========================
    $updateData = [
        'status' => $status,
        'payment_status' => $paymentStatus ?? $appointment['payment_status'],
        'updated_at' => date('Y-m-d H:i:s')
    ];

    $now = date('Y-m-d H:i:s');

    if ($status === 'confirmed') $updateData['confirmed_at'] = $now;
    if ($status === 'completed') $updateData['completed_at'] = $now;
    if ($status === 'cancelled') $updateData['cancelled_at'] = $now;

    $appointmentClass->update($id, $updateData);

    Response::success('Appointment updated successfully');
} catch (Exception $e) {
    Response::error('Server error', 500);
}
