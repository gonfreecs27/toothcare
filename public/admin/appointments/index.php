<?php
session_start();

$additional_js = [
    '/assets/js/appointment.js'
];

$additional_css = [
    '/assets/css/appointment.css'
];

require_once(__DIR__ . '/../../../includes/header_app.php');
require_once(__DIR__ . '/../../../includes/sidebar.php');
?>

<div class="main-wrapper">
    <div class="content">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="fw-bold mb-0">Appointment Calendar</h3>
                <small class="text-muted">ToothCare Scheduling System</small>
            </div>

            <div class="d-flex gap-2">
                <button id="btnToday" class="btn btn-outline-secondary">
                    <i class="bi bi-calendar-day"></i> Today
                </button>

                <button id="btnNewAppointment" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> New Appointment
                </button>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div class="d-flex gap-3 flex-wrap">
                    <div class="schedule-legend">
                        <div class="legend-item pending">
                            <span></span> Pending
                        </div>

                        <div class="legend-item confirmed">
                            <span></span> Confirmed
                        </div>

                        <div class="legend-item completed">
                            <span></span> Completed
                        </div>

                        <div class="legend-item cancelled">
                            <span></span> Cancelled
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <select id="filterStatus" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="stat-card bg-primary-subtle">
                    <h6>Total</h6>
                    <h3 id="totalAppointments">0</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card bg-success-subtle">
                    <h6>Confirmed</h6>
                    <h3 id="confirmedAppointments">0</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card bg-warning-subtle">
                    <h6>Pending</h6>
                    <h3 id="pendingAppointments">0</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card bg-danger-subtle">
                    <h6>Cancelled</h6>
                    <h3 id="cancelledAppointments">0</h3>
                </div>
            </div>

        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>

    </div>

    <?php include __DIR__ . '/../../../includes/footer_app.php'; ?>
</div>

<div class="modal fade" id="appointmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Appointment Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div id="apptDetails"></div>
            </div>

            <div class="modal-footer">
                <button id="btnDelete" class="btn btn-danger btn-sm">Delete</button>
                <button id="btnEdit" class="btn btn-primary btn-sm">Edit</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="addAppointmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-calendar-plus me-2"></i>New Appointment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="appointmentForm">

                    <div class="mb-2">
                        <label class="form-label">Patient</label>
                        <select name="patient_id" class="form-select" required>
                            <option value="">Select Patient</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Dentist</label>
                        <select name="dentist_id" class="form-select" required>
                            <option value="">Select Dentist</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Services</label>
                        <div id="serviceList" class="border rounded p-2" style="max-height:140px;overflow:auto;"></div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Date</label>
                        <input type="date" name="appointment_date" class="form-control" required>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">Start</label>
                            <input type="time" name="start_time" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">End</label>
                            <input type="time" name="end_time" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-2 mt-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Reason</label>
                        <textarea name="reason" class="form-control" rows="2"></textarea>
                    </div>

                </form>
            </div>

            <div class="modal-footer border-0">
                <button class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="btnSaveAppointment">Save</button>
            </div>

        </div>
    </div>
</div>