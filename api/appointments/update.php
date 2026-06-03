<?php
require '../../init.php';
Permission::authorize(['admin', 'staff', 'dentist']);

Core::loadModel("Appointment");
Core::loadModel("Payment");

$appointmentClass = new Appointment();
$paymentClass = new Payment();

try {

    $id = (int) ($_POST['id'] ?? 0);

    $patient_id = trim($_POST['patient_id'] ?? '');
    $dentist_id = trim($_POST['dentist_id'] ?? '');
    $date = trim($_POST['appointment_date'] ?? '');
    $start_time = trim($_POST['start_time'] ?? '');
    $end_time = trim($_POST['end_time'] ?? '');
    $status = trim($_POST['status'] ?? 'pending');
    $reason = trim($_POST['reason'] ?? '');

    $payment_status = $_POST['payment_status'] ?? null;
    $payment_method = $_POST['payment_method'] ?? 'cash';

    $services = $_POST['services'] ?? [];
    $services = array_values(array_filter(array_map('intval', $services)));

    if (!$id) {
        throw new Exception('Appointment ID is required');
    }

    if (!$patient_id) {
        throw new Exception('Patient is required');
    }

    if (!$dentist_id) {
        throw new Exception('Dentist is required');
    }

    if (!$date) {
        throw new Exception('Date is required');
    }

    if (!$start_time) {
        throw new Exception('Start time is required');
    }

    if (!$end_time) {
        throw new Exception('End time is required');
    }

    if (empty($services)) {
        throw new Exception('Select a service.');
    }

    $appointment = $appointmentClass->find($id);

    if (!$appointment) {
        throw new Exception("Appointment not found.");
    }

    if ($appointment['status'] === 'completed') {
        throw new Exception("This transaction has already been completed.");
    }

    if ($appointment['status'] === 'cancelled') {
        throw new Exception("This transaction has already been cancelled.");
    }

    $startDateTime = DateTime::createFromFormat('Y-m-d H:i', "$date $start_time");
    $endDateTime   = DateTime::createFromFormat('Y-m-d H:i', "$date $end_time");

    if (!$startDateTime || !$endDateTime) {
        throw new Exception('Invalid date or time format');
    }

    if ($endDateTime <= $startDateTime) {
        throw new Exception('End time must be greater than start time');
    }

    $start = $startDateTime->format('Y-m-d H:i:s');
    $end   = $endDateTime->format('Y-m-d H:i:s');

    if ($appointmentClass->findConflict($dentist_id, $start, $end, $id)) {
        throw new Exception('Dentist already has an appointment at this time');
    }

    $updated = $appointmentClass->update($id, [
        'patient_id' => $patient_id,
        'dentist_id' => $dentist_id,
        'appointment_start' => $start,
        'appointment_end' => $end,
        'status' => $status,
        'reason' => $reason,
        'services' => $services
    ]);

    if (!$updated) {
        throw new Exception('Failed to update appointment');
    }

    $payment = $paymentClass->findByAppointment($id);

    // create payment if missing
    if (!$payment) {
        $paymentClass->createFromAppointment($id, [
            'payment_method' => $payment_method,
            'reference_no' => $paymentClass->generateReferenceNo()
        ]);
    }

    $newAmount = $paymentClass->calculateAppointmentAmount($id);
    $paymentClass->updateAmountByAppointment($id, $newAmount);
    Response::success('Appointment updated successfully');
} catch (Exception $e) {
    Response::error($e->getMessage(), 422);
}
