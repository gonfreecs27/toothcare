<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'staff') {
    header("Location: /login.php");
    exit;
}

$user = $_SESSION['user'];

include __DIR__ . '/../../includes/header_app.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="main-wrapper">

    <div class="content">

        <!-- HEADER -->
        <div class="dashboard-header mb-4">

            <div>

                <h3 class="fw-bold mb-1">
                    Staff Dashboard
                </h3>

                <p class="text-muted mb-0">
                    Welcome back,
                    <span class="text-primary fw-semibold">
                        <?= htmlspecialchars($user['name']) ?>
                    </span>
                </p>

            </div>

            <div class="dashboard-date">

                <i class="bi bi-calendar3"></i>

                <?= date('F d, Y') ?>

            </div>

        </div>

        <!-- STATS -->
        <div class="row g-4">

            <div class="col-lg-4 col-md-6">

                <div class="dashboard-card appointments-card">

                    <div class="card-icon">
                        <i class="bi bi-calendar2-check-fill"></i>
                    </div>

                    <div>

                        <span class="card-title">
                            Today's Appointments
                        </span>

                        <h2 class="card-value">
                            18
                        </h2>

                        <small class="text-success">
                            5 upcoming schedules
                        </small>

                    </div>

                </div>

            </div>

            <div class="col-lg-4 col-md-6">

                <div class="dashboard-card payments-card">

                    <div class="card-icon">
                        <i class="bi bi-wallet2"></i>
                    </div>

                    <div>

                        <span class="card-title">
                            Payments Collected
                        </span>

                        <h2 class="card-value">
                            ₱12,500
                        </h2>

                        <small class="text-muted">
                            Today's collection
                        </small>

                    </div>

                </div>

            </div>

            <div class="col-lg-4 col-md-6">

                <div class="dashboard-card patients-card">

                    <div class="card-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <div>

                        <span class="card-title">
                            Waiting Patients
                        </span>

                        <h2 class="card-value">
                            7
                        </h2>

                        <small class="text-warning">
                            Requires assistance
                        </small>

                    </div>

                </div>

            </div>

        </div>

        <!-- MAIN CONTENT -->
        <div class="row mt-4 g-4">

            <!-- LEFT -->
            <div class="col-lg-8">

                <div class="card dashboard-panel shadow-sm border-0">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div>

                                <h5 class="fw-bold mb-1">
                                    Today's Schedule
                                </h5>

                                <p class="text-muted small mb-0">
                                    Upcoming patient appointments
                                </p>

                            </div>

                            <a href="/staff/appointments.php"
                                class="btn btn-light btn-sm">

                                View All

                            </a>

                        </div>

                        <div class="schedule-list">

                            <div class="schedule-item">

                                <div class="schedule-time">
                                    9:00 AM
                                </div>

                                <div class="schedule-info">

                                    <div class="fw-semibold">
                                        Juan Dela Cruz
                                    </div>

                                    <small class="text-muted">
                                        Dental Cleaning • Dr. Santos
                                    </small>

                                </div>

                                <span class="badge bg-success-subtle text-success">
                                    Confirmed
                                </span>

                            </div>

                            <div class="schedule-item">

                                <div class="schedule-time">
                                    10:30 AM
                                </div>

                                <div class="schedule-info">

                                    <div class="fw-semibold">
                                        Maria Reyes
                                    </div>

                                    <small class="text-muted">
                                        Tooth Extraction • Dr. Cruz
                                    </small>

                                </div>

                                <span class="badge bg-warning-subtle text-warning">
                                    Pending
                                </span>

                            </div>

                            <div class="schedule-item">

                                <div class="schedule-time">
                                    1:00 PM
                                </div>

                                <div class="schedule-info">

                                    <div class="fw-semibold">
                                        Carlo Mendoza
                                    </div>

                                    <small class="text-muted">
                                        Consultation • Dr. Santos
                                    </small>

                                </div>

                                <span class="badge bg-primary-subtle text-primary">
                                    Ongoing
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- RIGHT -->
            <div class="col-lg-4">

                <div class="card dashboard-panel shadow-sm border-0 mb-4">

                    <div class="card-body">

                        <h5 class="fw-bold mb-4">
                            Quick Actions
                        </h5>

                        <div class="d-grid gap-3">

                            <a href="/staff/appointments.php"
                                class="quick-action-btn">

                                <i class="bi bi-calendar-plus-fill"></i>

                                <span>
                                    Manage Appointments
                                </span>

                            </a>

                            <a href="/staff/payments.php"
                                class="quick-action-btn">

                                <i class="bi bi-cash-stack"></i>

                                <span>
                                    Process Payments
                                </span>

                            </a>

                        </div>

                    </div>

                </div>

                <div class="card dashboard-panel shadow-sm border-0">

                    <div class="card-body">

                        <h5 class="fw-bold mb-3">
                            Staff Notes
                        </h5>

                        <div class="alert alert-light border">

                            <small class="text-muted">

                                Please verify all patient records before confirming appointments.

                            </small>

                        </div>

                        <div class="alert alert-light border mb-0">

                            <small class="text-muted">

                                End-of-day payment reports must be submitted before 6 PM.

                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <?php include __DIR__ . '/../../includes/footer_app.php'; ?>

</div>