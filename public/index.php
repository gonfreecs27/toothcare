<?php
require '../init.php';

if (isset($_SESSION['user'])) {
    Core::redirect("loading");
}

Core::loadModel("Appointment");
Core::loadModel("Feedback");
Core::loadModel("Service");
Core::loadModel("Dentist");
Core::loadModel("Patient");

$feedbackModel = new Feedback();
$appointmentModel = new Appointment();
$serviceModel = new Service();
$dentistModel = new Dentist();
$patientModel = new Patient();

$avgRating = $feedbackModel->averageRating();
$totalFeedbacks = $feedbackModel->totalApproved();

$monthlyAppointments = $appointmentModel->appointmentsThisMonth();
$totalAppointments = $appointmentModel->totalAppointments();

$totalPatients = $patientModel->countPatients();
$totalDentists = $dentistModel->countActiveDentists();
$totalServices = $serviceModel->totalServices();

$featuredFeedbacks = $feedbackModel->getFeatured();

Component::header(true);
?>

<!-- HERO -->
<section class="hero">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-lg-6">

                <div class="hero-badge">
                    <i class="bi bi-shield-check"></i>
                    Trusted Dental Clinic Platform
                </div>

                <h1 class="fw-bold display-3">
                    <?= BRAND_NAME_FIRST ?><span class="text-primary"><?= BRAND_NAME_SECOND ?></span>
                </h1>

                <p class="fs-4 text-muted mt-3">
                    Smarter Dental Care Management
                </p>

                <p class="text-muted">
                    Manage appointments, patient records, services, and feedback
                    through one modern platform designed for dental clinics.
                </p>

                <div class="hero-features mt-4">

                    <div>
                        <i class="bi bi-check-circle-fill text-success"></i>
                        Online Appointment Booking
                    </div>

                    <div>
                        <i class="bi bi-check-circle-fill text-success"></i>
                        Digital Patient Records
                    </div>

                    <div>
                        <i class="bi bi-check-circle-fill text-success"></i>
                        Feedback & Service Monitoring
                    </div>

                </div>

                <div class="mt-4">
                    <a href="<?= PROJECT_BASE ?>login" class="btn btn-primary btn-lg px-4 me-2">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Get Started
                    </a>

                    <a href="<?= PROJECT_BASE ?>appointment" class="btn btn-outline-primary btn-lg px-4">
                        <i class="bi bi-calendar-check"></i>
                        Book Appointment
                    </a>
                </div>

            </div>

            <div class="col-lg-6 text-center position-relative">

                <img src="<?= PROJECT_BASE ?>assets/images/bg.png"
                    class="hero-img img-fluid">

                <div class="hero-floating-card card-1">
                    <i class="bi bi-calendar-check-fill"></i>
                    <?= number_format($monthlyAppointments) ?> Monthly Appointments
                </div>

                <div class="hero-floating-card card-2">
                    <i class="bi bi-star-fill"></i>
                    <?= $avgRating ?> Patient Rating
                </div>
            </div>
        </div>
    </div>

</section>

<section class="stats-strip">
    <div class="container">
        <div class="row text-center">

            <div class="col-md-3">
                <h3><?= number_format($totalAppointments); ?></h3>
                <p>Total Appointments</p>
            </div>

            <div class="col-md-3">
                <h3><?= number_format($totalServices); ?></h3>
                <p>Dental Services</p>
            </div>

            <div class="col-md-3">
                <h3><?= number_format($totalFeedbacks); ?></h3>
                <p>Patient Satisfaction</p>
            </div>

            <div class="col-md-3">
                <h3>24/7</h3>
                <p>System Access</p>
            </div>

        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="container py-5">

    <h2 class="section-title text-center">
        <i class="bi bi-grid-1x2-fill text-primary"></i>
        Clinic Features
    </h2>

    <p class="section-subtitle text-center mb-5">
        Everything you need to manage your dental clinic efficiently
    </p>

    <div class="row g-4">

        <div class="col-md-4">
            <div class="feature-blue">
                <?php Component::featureCard(
                    "bi-calendar-check",
                    "Appointments",
                    "Manage patient schedules easily and efficiently."
                ); ?>
            </div>
        </div>

        <div class="col-md-4">
            <div class="feature-green">
                <?php Component::featureCard(
                    "bi-people",
                    "Patient Records",
                    "Secure and organized dental history management."
                ); ?>
            </div>
        </div>

        <div class="col-md-4">
            <div class="feature-orange">
                <?php Component::featureCard(
                    "bi-star",
                    "Feedback System",
                    "Collect feedbacks to improve service quality."
                ); ?>
            </div>
        </div>

    </div>

</section>

<!-- ACTIONS -->
<section class="cta-section">

    <div class="container text-center">

        <h2 class="fw-bold">
            Ready to Schedule Your Visit?
        </h2>

        <p class="text-muted mb-4">
            Book your appointment online and receive quality dental care.
        </p>

        <a href="<?= PROJECT_BASE ?>appointment" class="btn btn-primary btn-lg px-5">
            <i class="bi bi-calendar-check"></i>
            Book Appointment
        </a>

        <a href="<?= PROJECT_BASE ?>feedback" class="btn btn-warning btn-lg px-5">
            <i class="bi bi-chat-dots"></i>
            Send Feedback
        </a>
    </div>

</section>

<!-- ABOUT -->
<section class="container py-5">
    <div class="row align-items-center">

        <div class="col-lg-6">

            <h2 class="fw-bold">
                <i class="bi bi-heart-pulse-fill text-danger"></i>
                About <?= BRAND_NAME ?>
            </h2>

            <p class="text-muted mt-3">
                <?= BRAND_NAME ?> is a modern dental clinic management system designed
                to improve workflow efficiency, enhance patient experiences,
                and simplify clinic operations.
            </p>

            <p class="text-muted">
                From appointment scheduling to patient management,
                everything is available in one centralized platform.
            </p>

        </div>

        <div class="col-lg-6">
            <div class="row g-3">

                <div class="col-6">
                    <?php Component::statBox(number_format($totalPatients), "Patients"); ?>
                </div>

                <div class="col-6">
                    <?php Component::statBox(number_format($totalServices), "Services"); ?>
                </div>

                <div class="col-6">
                    <?php Component::statBox(number_format($totalDentists), "Dentists"); ?>
                </div>

                <div class="col-6">
                    <?php Component::statBox("24/7", "System Access"); ?>
                </div>

            </div>
        </div>

    </div>

</section>

<?php if (!empty($featuredFeedbacks)): ?>

    <section class="container py-5">

        <div class="text-center mb-5">
            <h2 class="fw-bold">
                What Our Patients Say
            </h2>

            <p class="text-muted">
                Real feedback from our patients.
            </p>
        </div>

        <div class="row g-4">

            <?php foreach ($featuredFeedbacks as $feedback): ?>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">

                        <div class="card-body">

                            <div class="mb-3 text-warning">

                                <?php for ($i = 1; $i <= 5; $i++): ?>

                                    <i class="bi bi-star-fill <?= $i <= $feedback['rating'] ? '' : 'text-secondary' ?>"></i>

                                <?php endfor; ?>

                            </div>

                            <p class="text-muted">
                                "<?= htmlspecialchars($feedback['message']) ?>"
                            </p>

                            <div class="fw-bold">
                                <?= htmlspecialchars($feedback['name']) ?>
                            </div>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </section>

<?php endif; ?>

<?php Component::footer(true); ?>