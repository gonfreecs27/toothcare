<?php
require '../../init.php';

Core::loadModel("Appointment");
$appointmentClass = new Appointment();

try {
    $start = $_POST['start'] ?? null;
    $end   = $_POST['end'] ?? null;

    $appointments = $appointmentClass->getAppointments($start, $end);

    $cleanEvents = [];

    foreach ($appointments as $a) {
        unset($a['extendedProps']['patient_name']);
        unset($a['extendedProps']['dentist_name']);
        $serviceNames = [];

        if (!empty($a['extendedProps']['services'])) {
            foreach ($a['extendedProps']['services'] as $service) {
                $serviceNames[] = $service['name'];
            }
        }

        $a['title'] = !empty($serviceNames)
            ? implode(', ', $serviceNames)
            : 'Dental Appointment';
        $cleanEvents[] = $a;
    }

    Response::success('Appointments retrieved successfully', [
        'events' => $cleanEvents,
    ]);
} catch (Exception $e) {
    Response::error($e->getMessage(), 500);
}
