<?php
require '../../init.php';

Permission::authorize(['staff']);

Core::loadModel("Appointment");
Core::loadModel("Payment");

$user = $_SESSION['user'];

$appointmentModel = new Appointment();
$paymentModel = new Payment();

// =========================
// STAFF STATS
// =========================
$todayAppointments = $appointmentModel->countToday();
$pendingAppointments = $appointmentModel->countPending();
$confirmedAppointments = $appointmentModel->countConfirmed();

$paymentsToday = $paymentModel->todayRevenue();

// =========================
// TODAY SCHEDULE
// =========================
$todaySchedule = $appointmentModel->todaySchedule(10);

Component::header();
Component::sidebar();
?>

<div class="main-wrapper">
    <div class="content">

        <!-- HEADER -->
        <div class="dashboard-header mb-4">

            <div>
                <h3 class="fw-bold mb-1">
                    Welcome back,
                    <span class="text-primary">
                        <?= htmlspecialchars($user['name']) ?>
                    </span>
                </h3>

                <p class="text-muted mb-0">
                    Staff operations dashboard overview.
                </p>
            </div>

            <div class="dashboard-date">
                <i class="bi bi-calendar3"></i>
                <?= date('F d, Y') ?>
            </div>

        </div>

        <!-- STATS -->
        <div class="row g-4">

            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card card-primary">
                    <div class="card-icon">
                        <i class="bi bi-calendar2-check-fill"></i>
                    </div>
                    <div class="card-details">
                        <span class="card-title">Today's Appointments</span>
                        <h2 class="card-value"><?= $todayAppointments ?></h2>
                        <small class="text-muted">Scheduled today</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card card-pending">
                    <div class="card-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="card-details">
                        <span class="card-title">Pending</span>
                        <h2 class="card-value"><?= $pendingAppointments ?></h2>
                        <small class="text-warning">Needs action</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card card-success">
                    <div class="card-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div class="card-details">
                        <span class="card-title">Confirmed</span>
                        <h2 class="card-value"><?= $confirmedAppointments ?></h2>
                        <small class="text-primary">Ready for service</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card card-danger">
                    <div class="card-icon">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                    <div class="card-details">
                        <span class="card-title">Payments Today</span>
                        <h2 class="card-value">
                            <?= number_format($paymentsToday, 2) ?>
                        </h2>
                        <small class="text-success">Collected today</small>
                    </div>
                </div>
            </div>

        </div>

        <!-- MAIN CONTENT -->
        <div class="row mt-4 g-4">

            <!-- LEFT PANEL -->
            <div class="col-lg-4">

                <div class="card shadow-sm border-0">
                    <div class="card-body">

                        <h5 class="fw-bold mb-4">Appointment Overview</h5>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Pending</span>
                            <strong><?= $pendingAppointments ?></strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Confirmed</span>
                            <strong><?= $confirmedAppointments ?></strong>
                        </div>

                        <hr>

                        <h6 class="fw-bold mb-3">Quick Actions</h6>

                        <div class="d-grid gap-2">

                            <a href="<?= PROJECT_BASE ?>staff/appointments"
                                class="btn btn-outline-primary">
                                <i class="bi bi-calendar me-2"></i>
                                Manage Appointments
                            </a>

                            <a href="<?= PROJECT_BASE ?>staff/payments"
                                class="btn btn-outline-success">
                                <i class="bi bi-cash-coin me-2"></i>
                                Manage Payments
                            </a>

                            <a href="<?= PROJECT_BASE ?>staff/patients"
                                class="btn btn-outline-info">
                                <i class="bi bi-people-fill me-2"></i>
                                Patient Records
                            </a>

                        </div>

                    </div>
                </div>

            </div>

            <!-- RIGHT PANEL -->
            <div class="col-lg-8">

                <div class="card shadow-sm border-0">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div>
                                <h5 class="fw-bold mb-1">Today's Schedule</h5>
                                <p class="text-muted small mb-0">All clinic appointments</p>
                            </div>

                            <a href="<?= PROJECT_BASE ?>staff/appointments"
                                class="btn btn-light btn-sm">
                                View Calendar
                            </a>

                        </div>

                        <?php if (empty($todaySchedule)): ?>

                            <div class="text-center py-5">
                                <i class="bi bi-calendar-x fs-1 text-muted"></i>
                                <p class="text-muted mt-3 mb-0">
                                    No appointments today.
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