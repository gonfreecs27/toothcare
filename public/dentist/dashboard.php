<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'dentist') {
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
                    Dentist Dashboard
                </h3>

                <p class="text-muted mb-0">

                    Welcome back,
                    <span class="text-primary fw-semibold">
                        Dr. <?= htmlspecialchars($user['name']) ?>
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
                            12
                        </h2>

                        <small class="text-success">
                            3 upcoming schedules
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
                            Active Patients
                        </span>

                        <h2 class="card-value">
                            48
                        </h2>

                        <small class="text-muted">
                            Under your care
                        </small>

                    </div>

                </div>

            </div>

            <div class="col-lg-4 col-md-6">

                <div class="dashboard-card card-revenue">

                    <div class="card-icon">
                        <i class="bi bi-clipboard2-pulse-fill"></i>
                    </div>

                    <div>

                        <span class="card-title">
                            Completed Treatments
                        </span>

                        <h2 class="card-value">
                            26
                        </h2>

                        <small class="text-primary">
                            This month
                        </small>

                    </div>

                </div>

            </div>

        </div>

        <!-- CONTENT -->
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
                                    Upcoming consultations and procedures
                                </p>

                            </div>

                            <a href="/dentist/schedule.php"
                                class="btn btn-light btn-sm">

                                View Full Schedule

                            </a>

                        </div>

                        <div class="schedule-list">

                            <div class="schedule-item">

                                <div class="schedule-time">
                                    9:00 AM
                                </div>

                                <div class="schedule-info">

                                    <div class="fw-semibold">
                                        Anna Reyes
                                    </div>

                                    <small class="text-muted">
                                        Dental Cleaning
                                    </small>

                                </div>

                                <span class="badge bg-success-subtle text-success">
                                    Confirmed
                                </span>

                            </div>

                            <div class="schedule-item">

                                <div class="schedule-time">
                                    11:00 AM
                                </div>

                                <div class="schedule-info">

                                    <div class="fw-semibold">
                                        Michael Santos
                                    </div>

                                    <small class="text-muted">
                                        Root Canal Procedure
                                    </small>

                                </div>

                                <span class="badge bg-primary-subtle text-primary">
                                    Ongoing
                                </span>

                            </div>

                            <div class="schedule-item">

                                <div class="schedule-time">
                                    2:00 PM
                                </div>

                                <div class="schedule-info">

                                    <div class="fw-semibold">
                                        Carla Mendoza
                                    </div>

                                    <small class="text-muted">
                                        Tooth Extraction
                                    </small>

                                </div>

                                <span class="badge bg-warning-subtle text-warning">
                                    Pending
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="card dashboard-panel shadow-sm border-0 mt-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div>

                                <h5 class="fw-bold mb-1">
                                    Recent Patient Activity
                                </h5>

                                <p class="text-muted small mb-0">
                                    Latest updates from your patients
                                </p>

                            </div>

                        </div>

                        <div class="activity-list">

                            <div class="activity-item">

                                <div class="activity-icon bg-primary-subtle text-primary">
                                    <i class="bi bi-file-earmark-medical"></i>
                                </div>

                                <div class="activity-content">

                                    <div class="fw-semibold">
                                        Treatment record updated
                                    </div>

                                    <small class="text-muted">
                                        Updated procedure notes for Maria Cruz
                                    </small>

                                </div>

                                <small class="text-muted">
                                    20 mins ago
                                </small>

                            </div>

                            <div class="activity-item">

                                <div class="activity-icon bg-success-subtle text-success">
                                    <i class="bi bi-check-circle"></i>
                                </div>

                                <div class="activity-content">

                                    <div class="fw-semibold">
                                        Procedure completed
                                    </div>

                                    <small class="text-muted">
                                        Dental filling completed successfully
                                    </small>

                                </div>

                                <small class="text-muted">
                                    1 hour ago
                                </small>

                            </div>

                            <div class="activity-item">

                                <div class="activity-icon bg-warning-subtle text-warning">
                                    <i class="bi bi-clock-history"></i>
                                </div>

                                <div class="activity-content">

                                    <div class="fw-semibold">
                                        Appointment rescheduled
                                    </div>

                                    <small class="text-muted">
                                        Patient moved consultation to Friday
                                    </small>

                                </div>

                                <small class="text-muted">
                                    2 hours ago
                                </small>

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

                            <a href="/dentist/schedule.php"
                                class="quick-action-btn">

                                <i class="bi bi-calendar-week-fill"></i>

                                <span>
                                    View Schedule
                                </span>

                            </a>

                            <a href="/dentist/patients.php"
                                class="quick-action-btn">

                                <i class="bi bi-person-lines-fill"></i>

                                <span>
                                    Manage Patients
                                </span>

                            </a>

                        </div>

                    </div>

                </div>

                <div class="card dashboard-panel shadow-sm border-0">

                    <div class="card-body">

                        <h5 class="fw-bold mb-3">
                            Reminders
                        </h5>

                        <div class="alert alert-light border">

                            <small class="text-muted">

                                Ensure treatment records are updated after every consultation.

                            </small>

                        </div>

                        <div class="alert alert-light border mb-0">

                            <small class="text-muted">

                                Review tomorrow's appointments before end of shift.

                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <?php include __DIR__ . '/../../includes/footer_app.php'; ?>

</div>