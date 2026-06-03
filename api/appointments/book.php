<?php
require '../../init.php';

Core::loadModel("Appointment");
Core::loadModel("Payment");
Core::loadModel("Patient");
Core::loadModel("Service");

$appointmentClass = new Appointment();
$paymentClass = new Payment();
$patientClass = new Patient();
$serviceClass = new Service();

try {

    // =========================
    // PATIENT INFO (from wizard)
    // =========================
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname  = trim($_POST['lastname'] ?? '');
    $contact   = trim($_POST['contact'] ?? '');
    $email     = trim($_POST['email'] ?? '');

    // =========================
    // APPOINTMENT DATA
    // =========================
    $dentist_id = trim($_POST['dentist_id'] ?? '');
    $date       = trim($_POST['date'] ?? '');
    $start_time = trim($_POST['start_time'] ?? '');
    $end_time   = trim($_POST['end_time'] ?? '');
    $reason     = trim($_POST['reason'] ?? '');

    $services = $_POST['services'] ?? [];
    $services = array_values(array_filter(array_map('intval', $services)));

    // =========================
    // VALIDATION
    // =========================
    if (!$firstname || !$lastname) {
        throw new Exception('Patient name is required');
    }

    if (!$contact) {
        throw new Exception('Contact is required');
    }

    if (!$dentist_id) {
        throw new Exception('Dentist is required');
    }

    if (!$date) {
        throw new Exception('Date is required');
    }

    if (!$start_time || !$end_time) {
        throw new Exception('Schedule is required');
    }

    if (empty($services)) {
        throw new Exception('Please select at least one service');
    }

    // =========================
    // TIME VALIDATION
    // =========================
    $startDateTime = DateTime::createFromFormat('Y-m-d H:i', "$date $start_time");
    $endDateTime   = DateTime::createFromFormat('Y-m-d H:i', "$date $end_time");

    if (!$startDateTime || !$endDateTime) {
        throw new Exception('Invalid schedule format');
    }

    if ($endDateTime <= $startDateTime) {
        throw new Exception('End time must be greater than start time');
    }

    // enforce clinic hours (9AM - 5PM)
    $open = (clone $startDateTime)->setTime(9, 0);
    $close = (clone $startDateTime)->setTime(17, 0);

    if ($startDateTime < $open || $endDateTime > $close) {
        throw new Exception('Appointment must be between 9:00 AM and 5:00 PM');
    }

    $start = $startDateTime->format('Y-m-d H:i:s');
    $end   = $endDateTime->format('Y-m-d H:i:s');

    // =========================
    // CONFLICT CHECK
    // =========================
    $existing = $appointmentClass->findConflict($dentist_id, $start, $end);

    if ($existing) {
        throw new Exception('Selected schedule is already booked');
    }

    // =========================
    // CREATE / FIND PATIENT
    // =========================
    $patient = $patientClass->findByEmail($email);
    if ($patient) {
        $patient_id = $patient['id'];
    } else {
        $patient_id = $patientClass->create([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'birthdate' => null,
            'gender' => null,
            'contact' => $contact,
            'email' => $email,
            'address' => null,
            'civil_status' => null,
            'status' => 'active'
        ]);
    }

    // =========================
    // CALCULATE TOTAL
    // =========================
    $serviceList = $serviceClass->getByIds($services);

    $total = 0;
    foreach ($serviceList as $s) {
        $total += (float)$s['price'];
    }

    // =========================
    // CREATE APPOINTMENT
    // =========================
    $appointmentId = $appointmentClass->create([
        'patient_id' => $patient_id,
        'dentist_id' => $dentist_id,
        'appointment_start' => $start,
        'appointment_end' => $end,
        'status' => 'pending',
        'reason' => $reason,
        'services' => $services,
        'total_amount' => $total
    ]);

    if (!$appointmentId) {
        throw new Exception('Failed to create appointment');
    }

    // =========================
    // PAYMENT RECORD
    // =========================
    $paymentClass->createFromAppointment($appointmentId, [
        'amount' => $total,
        'payment_method' => 'cash',
        'reference_no' => $paymentClass->generateReferenceNo()
    ]);

    // =========================
    // RESPONSE
    // =========================
    Response::success('Appointment booked successfully', [
        'appointment_id' => $appointmentId,
        'total' => $total
    ]);
} catch (Exception $e) {
    Response::error($e->getMessage(), 422);
}
