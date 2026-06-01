<?php

require '../../init.php';

if (!Permission::hasAccess(['admin'])) {
    Core::redirect("login");
}

Core::loadModel("Patient");
Core::loadModel("Dentist");
Core::loadModel("Appointment");

$user = $_SESSION['user'];

$patientModel = new Patient();
$dentistModel = new Dentist();
$appointmentModel = new Appointment();

$totalPatients = $patientModel->countPatients();
$totalDentists = $dentistModel->countActiveDentists();

$todayAppointments = $appointmentModel->countToday();
$pendingAppointments = $appointmentModel->countPending();
$confirmedAppointments = $appointmentModel->countConfirmed();
$completedAppointments = $appointmentModel->countCompleted();
$cancelledAppointments = $appointmentModel->countCancelled();

$todaySchedule = $appointmentModel->todaySchedule(8);

Component::header();
Component::sidebar();
?>

<div class="main-wrapper">
    <div class="content">

        <div class="dashboard-header mb-4">

            <div>
                <h3 class="fw-bold mb-1">
                    Welcome back,
                    <span class="text-primary">
                        <?= htmlspecialchars($user['name']) ?>
                    </span>
                </h3>

                <p class="text-muted mb-0">
                    Here's a quick overview of your clinic.
                </p>
            </div>

            <div class="dashboard-date">
                <i class="bi bi-calendar3"></i>
                <?= date('F d, Y') ?>
            </div>

        </div>

        <!-- Statistics -->
        <div class="row g-4">

            <div class="col-lg-3 col-md-6">

                <div class="dashboard-card card-patients">

                    <div class="card-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <div class="card-details">
                        <span class="card-title">
                            Patients
                        </span>

                        <h2 class="card-value">
                            <?= number_format($totalPatients) ?>
                        </h2>

                        <small class="text-muted">
                            Registered patients
                        </small>
                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="dashboard-card card-dentists">

                    <div class="card-icon">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>

                    <div class="card-details">
                        <span class="card-title">
                            Active Dentists
                        </span>

                        <h2 class="card-value">
                            <?= number_format($totalDentists) ?>
                        </h2>

                        <small class="text-muted">
                            Available practitioners
                        </small>
                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="dashboard-card card-appointments">

                    <div class="card-icon">
                        <i class="bi bi-calendar2-check-fill"></i>
                    </div>

                    <div class="card-details">
                        <span class="card-title">
                            Today's Appointments
                        </span>

                        <h2 class="card-value">
                            <?= $todayAppointments ?>
                        </h2>

                        <small class="text-muted">
                            Scheduled today
                        </small>
                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="dashboard-card card-revenue">

                    <div class="card-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </div>

                    <div class="card-details">
                        <span class="card-title">
                            Pending
                        </span>

                        <h2 class="card-value">
                            <?= $pendingAppointments ?>
                        </h2>

                        <small class="text-warning">
                            Awaiting approval
                        </small>
                    </div>

                </div>

            </div>

        </div>

        <div class="row mt-4 g-4">

            <!-- Overview -->
            <div class="col-lg-4">

                <div class="card shadow-sm border-0">

                    <div class="card-body">

                        <h5 class="fw-bold mb-4">
                            Appointment Overview
                        </h5>

                        <div class="d-flex justify-content-between mb-3">
                            <span>Pending</span>
                            <strong><?= $pendingAppointments ?></strong>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span>Confirmed</span>
                            <strong><?= $confirmedAppointments ?></strong>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span>Completed</span>
                            <strong><?= $completedAppointments ?></strong>
                        </div>

                        <div class="d-flex justify-content-between mb-4">
                            <span>Cancelled</span>
                            <strong><?= $cancelledAppointments ?></strong>
                        </div>

                        <hr>

                        <h6 class="fw-bold mb-3">
                            Quick Actions
                        </h6>

                        <div class="d-grid gap-2">

                            <a href="<?= PROJECT_BASE ?>admin/patients"
                                class="btn btn-outline-primary">
                                <i class="bi bi-people-fill me-2"></i>
                                Manage Patients
                            </a>

                            <a href="<?= PROJECT_BASE ?>admin/dentists"
                                class="btn btn-outline-success">
                                <i class="bi bi-person-badge-fill me-2"></i>
                                Manage Dentists
                            </a>

                            <a href="<?= PROJECT_BASE ?>admin/appointments"
                                class="btn btn-outline-warning">
                                <i class="bi bi-calendar2-check-fill me-2"></i>
                                View Appointments
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Schedule -->
            <div class="col-lg-8">

                <div class="card shadow-sm border-0">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div>
                                <h5 class="fw-bold mb-1">
                                    Today's Schedule
                                </h5>

                                <p class="text-muted small mb-0">
                                    Upcoming appointments
                                </p>
                            </div>

                            <a href="<?= PROJECT_BASE ?>admin/appointments"
                                class="btn btn-light btn-sm">
                                View Calendar
                            </a>

                        </div>

                        <?php if (empty($todaySchedule)): ?>

                            <div class="text-center py-5">

                                <i class="bi bi-calendar-x fs-1 text-muted"></i>

                                <p class="text-muted mt-3 mb-0">
                                    No appointments scheduled today.
                                </p>

                            </div>

                        <?php else: ?>

                            <div class="table-responsive">

                                <table class="table align-middle">

                                    <thead>

                                        <tr>
                                            <th>Time</th>
                                            <th>Patient</th>
                                            <th>Dentist</th>
                                            <th>Status</th>
                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php foreach ($todaySchedule as $row): ?>

                                            <?php
                                            $badge = [
                                                'pending' => 'warning',
                                                'confirmed' => 'primary',
                                                'completed' => 'success',
                                                'cancelled' => 'danger'
                                            ];
                                            ?>

                                            <tr>
                                                <td>
                                                    <?= date('h:i A', strtotime($row['appointment_start'])) ?>
                                                </td>

                                                <td>
                                                    <?= htmlspecialchars($row['patient_name']) ?>
                                                </td>

                                                <td>
                                                    <?= htmlspecialchars($row['dentist_name']) ?>
                                                </td>

                                                <td>

                                                    <span class="badge bg-<?= $badge[$row['status']] ?? 'secondary' ?>">
                                                        <?= ucfirst($row['status']) ?>
                                                    </span>

                                                </td>

                                            </tr>

                                        <?php endforeach; ?>

                                    </tbody>

                                </table>

                            </div>

                        <?php endif; ?>

                    </div>

                </div>

            </div>
        </div>
    </div>
    <?php Component::footer(); ?>

</div>