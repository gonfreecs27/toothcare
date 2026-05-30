<?php
require '../../../init.php';
header('Content-Type: application/json');

if (!Permission::hasAccess(['admin', 'staff'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

try {
    Core::loadModel("Appointment");
    $appointmentClass = new Appointment();

    $id = $_POST['id'] ?? null;
    $status = $_POST['status'] ?? null;
    $paymentStatus = $_POST['payment_status'] ?? null;

    if (!$id || !$status) {
        http_response_code(422);
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    $allowedStatuses = ['pending', 'confirmed', 'cancelled', 'completed'];

    if (!in_array($status, $allowedStatuses)) {
        http_response_code(422);
        echo json_encode(['error' => 'Invalid status']);
        exit;
    }

    $appointment = $appointmentClass->find($id);

    if (!$appointment) {
        http_response_code(404);
        echo json_encode(['error' => 'Appointment not found']);
        exit;
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
        http_response_code(403);
        echo json_encode([
            'error' => "Cannot change status from {$currentStatus} to {$status}"
        ]);
        exit;
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

    echo json_encode([
        'message' => 'Appointment status updated successfully'
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Server error',
        'details' => $e->getMessage()
    ]);
}
