<?php
require_once '../init.php';

Core::loadModel("Dentist");
Core::loadModel("Service");

$dentistModel = new Dentist();
$serviceModel = new Service();

$dentists = $dentistModel->active();
$services = $serviceModel->all();

Component::header(true, null, [
    PROJECT_BASE . "assets/js/booking.js"
], [
    PROJECT_BASE . "assets/css/booking.css"
]);
?>

<div class="container py-5">

    <div class="text-center mb-4">
        <h1 class="fw-bold">Book an Appointment</h1>
        <p class="text-muted">Schedule your dental visit in a few easy steps</p>
    </div>

    <!-- PROGRESS -->
    <div class="booking-progress mb-4">

        <div class="progress" style="height:8px;">
            <div id="wizardProgress" class="progress-bar"></div>
        </div>

        <div class="row text-center mt-2 small">
            <div class="col">Services</div>
            <div class="col">Dentist</div>
            <div class="col">Schedule</div>
            <div class="col">Patient</div>
            <div class="col">Confirm</div>
        </div>

    </div>

    <div class="row g-4 booking-form">

        <!-- FORM -->
        <div class="col-lg-8">

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    <form id="appointmentWizard">

                        <!-- STEP 1 -->
                        <div class="wizard-step" data-step="1">

                            <h4 class="fw-bold mb-3">Select Services</h4>

                            <div class="row">

                                <?php foreach ($services as $service): ?>

                                    <div class="col-md-6 mb-3">

                                        <label class="service-card" data-price="<?= $service['price'] ?>">

                                            <input type="checkbox" name="services[]" value="<?= $service['id'] ?>">

                                            <div>
                                                <strong><?= htmlspecialchars($service['name']) ?></strong>
                                                <div class="text-muted">
                                                    Php <?= number_format($service['price'], 2) ?>
                                                </div>
                                            </div>

                                        </label>

                                    </div>

                                <?php endforeach; ?>

                            </div>

                        </div>

                        <!-- STEP 2 -->
                        <div class="wizard-step d-none" data-step="2">

                            <h4 class="fw-bold mb-3">Select Dentist</h4>

                            <div class="row">

                                <?php foreach ($dentists as $dentist): ?>

                                    <div class="col-md-6 mb-3">

                                        <label class="dentist-card">

                                            <input type="radio" name="dentist_id" value="<?= $dentist['id'] ?>">

                                            <div class="text-center p-3">

                                                <i class="bi bi-person-circle fs-1"></i>

                                                <h6 class="mt-2">
                                                    <?= htmlspecialchars($dentist['name']) ?>
                                                </h6>

                                            </div>

                                        </label>

                                    </div>

                                <?php endforeach; ?>

                            </div>

                        </div>

                        <!-- STEP 3 -->
                        <div class="wizard-step d-none" data-step="3">

                            <h4 class="fw-bold mb-3">Choose Schedule</h4>

                            <div class="row">

                                <div class="col-md-4 mb-3">
                                    <label>Date</label>
                                    <input type="date" id="appointmentDate" name="date" class="form-control" min="<?= date('Y-m-d') ?>">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label>Start Time</label>
                                    <select id="startTime" name="start_time" class="form-select mb-2"></select>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label>End Time</label>
                                    <select id="endTime" name="end_time" class="form-select"></select>
                                </div>

                            </div>

                        </div>

                        <!-- STEP 4 -->
                        <div class="wizard-step d-none" data-step="4">

                            <h4 class="fw-bold mb-3">Patient Info</h4>

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <input type="text" name="firstname" class="form-control" placeholder="First Name">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <input type="text" name="lastname" class="form-control" placeholder="Last Name">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <input type="text" name="contact" class="form-control" placeholder="Contact">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <input type="email" name="email" class="form-control" placeholder="Email">
                                </div>

                                <div class="col-12">
                                    <textarea name="reason" class="form-control" rows="3" placeholder="Reason for visit"></textarea>
                                </div>

                            </div>

                        </div>

                        <!-- STEP 5 -->
                        <div class="wizard-step d-none text-center" data-step="5">

                            <i class="bi bi-check-circle-fill text-success display-4"></i>

                            <h4 class="mt-3">Ready to Book</h4>

                            <p class="text-muted">Click submit to confirm your appointment</p>

                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <button type="button" id="btnPrev" class="btn btn-outline-secondary d-none">
                                <i class="bi bi-arrow-left me-1"></i> Previous
                            </button>

                            <button type="button" id="btnNext" class="btn btn-primary">
                                Next <i class="bi bi-arrow-right ms-1"></i>
                            </button>

                            <button type="submit" id="btnSubmit" class="btn btn-success d-none">
                                <i class="bi bi-calendar-check me-1"></i> Book Appointment
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>

        <!-- SUMMARY -->
        <div class="col-lg-4">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <h5 class="fw-bold">Summary</h5>
                    <hr>

                    <div id="summaryBox" class="text-muted">
                        Complete steps to see summary
                    </div>

                </div>

            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-calendar-week me-1"></i> Monthly Schedule
                    </h5>
                    <hr>

                    <div id="calendar"></div>
                </div>
            </div>

        </div>

    </div>

</div>

<?php Component::footer(true); ?>
