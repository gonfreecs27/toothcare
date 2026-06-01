<?php
require '../../../init.php';
Permission::authorize(['all']);

Component::header(false, null, [
    PROJECT_BASE . 'assets/js/appointment.js'
], [
    PROJECT_BASE . 'assets/css/appointment.css',
    PROJECT_BASE . 'assets/css/wizard.css'
]);
Component::sidebar();
?>

<div class="main-wrapper">
    <div class="content">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="fw-bold mb-0">Appointment Calendar</h3>
                <small class="text-muted"><?= BRAND_NAME ?> Scheduling System</small>
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
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="stat-card bg-success-subtle">
                    <h6>Completed</h6>
                    <h3 id="completedAppointments">0</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card bg-primary-subtle">
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

    <?php Component::footer(); ?>
</div>

<div class="modal fade" id="addAppointmentModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <!-- HEADER -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-calendar2-plus me-2"></i>
                    Manage Appointment
                </h5>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal">
                </button>
            </div>

            <!-- PROGRESS -->
            <div class="px-4 pt-4">

                <div class="wizard-steps d-flex justify-content-between">

                    <div class="wizard-step active" data-step="1">
                        <div class="step-icon">
                            <i class="bi bi-person-vcard"></i>
                        </div>
                        <small>Details</small>
                    </div>

                    <div class="wizard-step" data-step="2">
                        <div class="step-icon">
                            <i class="bi bi-heart-pulse"></i>
                        </div>
                        <small>Services</small>
                    </div>

                    <div class="wizard-step" data-step="3">
                        <div class="step-icon">
                            <i class="bi bi-credit-card"></i>
                        </div>
                        <small>Payment</small>
                    </div>

                    <div class="wizard-step" data-step="4">
                        <div class="step-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <small>Review</small>
                    </div>

                </div>

                <div class="progress mt-3" style="height:6px;">
                    <div
                        id="wizardProgress"
                        class="progress-bar"
                        style="width:25%">
                    </div>
                </div>

            </div>

            <div class="modal-body px-4">

                <!-- STATUS -->
                <div class="card border-0 bg-light mb-4">
                    <div class="card-body py-2">
                        <div class="row">

                            <div class="col-md-6">
                                <small class="text-muted">
                                    Appointment Status
                                </small>

                                <div>
                                    <span id="appointmentStatusBadge" class="status-chip chip-pending">
                                        <span class="dot"></span>
                                        Pending
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">
                                    Payment Status
                                </small>

                                <div>
                                    <span id="paymentStatusBadge" class="payment-chip chip-unpaid">
                                        <span class="dot"></span>
                                        Unpaid
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <form id="appointmentForm">

                    <!-- STEP 1 -->
                    <div class="wizard-page" data-page="1">

                        <h5 class="mb-3">
                            <i class="bi bi-person-vcard me-2"></i>
                            Appointment Details
                        </h5>

                        <div class="mb-3">
                            <label class="form-label">
                                Patient
                            </label>

                            <select
                                name="patient_id"
                                class="form-select"
                                required>
                                <option value="">
                                    Select Patient
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Dentist
                            </label>

                            <select
                                name="dentist_id"
                                class="form-select"
                                required>
                                <option value="">
                                    Select Dentist
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Reason for Visit
                            </label>

                            <textarea
                                name="reason"
                                class="form-control"
                                rows="4"></textarea>
                        </div>

                    </div>

                    <!-- STEP 2 -->
                    <div class="wizard-page d-none" data-page="2">

                        <h5 class="mb-3">
                            <i class="bi bi-heart-pulse me-2"></i>
                            Services & Schedule
                        </h5>

                        <div class="mb-3">
                            <label class="form-label">
                                Services
                            </label>

                            <select
                                id="services"
                                name="services[]"
                                multiple>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Total Amount
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    ₱
                                </span>

                                <input
                                    type="text"
                                    id="servicesTotal"
                                    class="form-control"
                                    value="0.00"
                                    readonly>
                            </div>
                        </div>

                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label">
                                    Date
                                </label>

                                <input
                                    type="date"
                                    name="appointment_date"
                                    class="form-control"
                                    required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    Start Time
                                </label>

                                <input
                                    type="time"
                                    name="start_time"
                                    class="form-control"
                                    required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    End Time
                                </label>

                                <input
                                    type="time"
                                    name="end_time"
                                    class="form-control"
                                    required>
                            </div>

                        </div>

                    </div>

                    <!-- STEP 3 -->
                    <div class="wizard-page d-none" data-page="3">

                        <h5 class="mb-3">
                            <i class="bi bi-credit-card me-2"></i>
                            Payment Information
                        </h5>

                        <div class="card border-0 bg-light">
                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Amount
                                        </label>

                                        <input
                                            type="text"
                                            id="paymentAmount"
                                            class="form-control"
                                            readonly>

                                    </div>

                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Payment Method
                                        </label>

                                        <select id="paymentMethod" class="form-select">
                                            <option value="cash">Cash</option>
                                            <option value="gcash">GCash</option>
                                            <option value="maya">Maya</option>
                                            <option value="card">Card</option>

                                        </select>

                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>

                    <!-- STEP 4 -->
                    <div class="wizard-page d-none" data-page="4">

                        <h5 class="mb-3">
                            <i class="bi bi-check-circle me-2"></i>
                            Review & Actions
                        </h5>

                        <div
                            id="appointmentReview"
                            class="card border-0 bg-light">
                            <div class="card-body"></div>
                        </div>
                        <div id="appointmentActions" class="d-flex gap-2 flex-wrap"></div>
                    </div>

                </form>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-light border"
                    data-bs-dismiss="modal">
                    Close
                </button>

                <button
                    type="button"
                    class="btn btn-outline-primary"
                    id="btnPrevStep"
                    style="display:none;">
                    <i class="bi bi-arrow-left"></i>
                    Previous
                </button>

                <button
                    type="button"
                    class="btn btn-primary"
                    id="btnNextStep">
                    Next
                    <i class="bi bi-arrow-right"></i>
                </button>

                <button
                    type="button"
                    class="btn btn-success d-none"
                    id="btnSaveAppointment">
                    <i class="bi bi-check-lg"></i>
                    Save Appointment
                </button>

            </div>

        </div>
    </div>
</div>