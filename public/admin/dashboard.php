<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /login");
    exit;
}

$user = $_SESSION['user'];

include __DIR__ . '/../../includes/header_app.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="main-wrapper">
    <button class="menu-toggle" id="menuToggle">
        <i class="bi bi-list"></i>
    </button>

    <div class="content">

        <!-- PAGE HEADER -->
        <div class="dashboard-header mb-4">

            <div>

                <h3 class="fw-bold mb-1">
                    Welcome back,
                    <span class="text-primary">
                        <?= htmlspecialchars($user['name']) ?>
                    </span>
                </h3>

                <p class="text-muted mb-0">
                    Here's what's happening in your clinic today.
                </p>

            </div>

            <div class="dashboard-date">

                <i class="bi bi-calendar3"></i>

                <?= date('F d, Y') ?>

            </div>

        </div>

        <!-- STATISTICS -->
        <div class="row g-4">

            <div class="col-lg-3 col-md-6">

                <div class="dashboard-card card-patients">

                    <div class="card-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <div class="card-details">

                        <span class="card-title">
                            Total Patients
                        </span>

                        <h2 class="card-value">
                            120
                        </h2>

                        <small class="card-growth text-success">
                            <i class="bi bi-arrow-up"></i>
                            +12 this month
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
                            Dentists
                        </span>

                        <h2 class="card-value">
                            8
                        </h2>

                        <small class="text-muted">
                            Active practitioners
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
                            45
                        </h2>

                        <small class="text-warning">
                            6 pending approvals
                        </small>

                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="dashboard-card card-revenue">

                    <div class="card-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>

                    <div class="card-details">

                        <span class="card-title">
                            Monthly Revenue
                        </span>

                        <h2 class="card-value">
                            ₱32,000
                        </h2>

                        <small class="text-success">
                            +18% from last month
                        </small>

                    </div>

                </div>

            </div>

        </div>

        <!-- QUICK ACTIONS -->
        <div class="row mt-4 g-4">

            <div class="col-lg-8">

                <div class="card dashboard-panel shadow-sm border-0">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div>

                                <h5 class="fw-bold mb-1">
                                    Recent Activity
                                </h5>

                                <p class="text-muted small mb-0">
                                    Latest clinic activities and transactions
                                </p>

                            </div>

                            <button class="btn btn-light btn-sm">
                                View All
                            </button>

                        </div>

                        <div class="activity-list">

                            <div class="activity-item">

                                <div class="activity-icon bg-primary-subtle text-primary">
                                    <i class="bi bi-calendar-check"></i>
                                </div>

                                <div class="activity-content">
                                    <div class="fw-semibold">
                                        New appointment booked
                                    </div>

                                    <small class="text-muted">
                                        Juan Dela Cruz booked with Dr. Santos
                                    </small>
                                </div>

                                <small class="text-muted">
                                    10 mins ago
                                </small>

                            </div>

                            <div class="activity-item">

                                <div class="activity-icon bg-success-subtle text-success">
                                    <i class="bi bi-cash"></i>
                                </div>

                                <div class="activity-content">
                                    <div class="fw-semibold">
                                        Payment received
                                    </div>

                                    <small class="text-muted">
                                        ₱2,500 treatment payment completed
                                    </small>
                                </div>

                                <small class="text-muted">
                                    30 mins ago
                                </small>

                            </div>

                            <div class="activity-item">

                                <div class="activity-icon bg-warning-subtle text-warning">
                                    <i class="bi bi-person-plus"></i>
                                </div>

                                <div class="activity-content">
                                    <div class="fw-semibold">
                                        New patient registered
                                    </div>

                                    <small class="text-muted">
                                        Maria Santos added to the system
                                    </small>
                                </div>

                                <small class="text-muted">
                                    1 hour ago
                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="card dashboard-panel shadow-sm border-0">

                    <div class="card-body">

                        <h5 class="fw-bold mb-4">
                            Quick Actions
                        </h5>

                        <div class="d-grid gap-3">

                            <a href="/admin/patients"
                                class="quick-action-btn">

                                <i class="bi bi-people-fill"></i>

                                <span>
                                    Manage Patients
                                </span>

                            </a>

                            <a href="/admin/appointments"
                                class="quick-action-btn">

                                <i class="bi bi-calendar2-check-fill"></i>

                                <span>
                                    View Appointments
                                </span>

                            </a>

                            <a href="/admin/payments"
                                class="quick-action-btn">

                                <i class="bi bi-cash-stack"></i>

                                <span>
                                    Payment Records
                                </span>

                            </a>

                            <a href="/admin/reports"
                                class="quick-action-btn">

                                <i class="bi bi-bar-chart-fill"></i>

                                <span>
                                    Generate Reports
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../../includes/footer_app.php'; ?>

</div>
