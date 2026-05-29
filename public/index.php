<?php
session_start();

$title = "ToothCare: Dental Clinic System";
include "../includes/header_public.php";
include "../includes/components.php";

if (isset($_SESSION['user'])) {
    header("Location: /loading");
    exit;
}
?>

<!-- HERO -->
<section class="hero py-5">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-lg-6">

                <!-- BRAND -->
                <h1 class="fw-bold display-4">
                    ToothCare
                </h1>

                <!-- TAGLINE -->
                <p class="text-muted fs-4 mt-3">
                    A Smarter Way to Manage Dental Clinics
                </p>

                <!-- DESCRIPTION -->
                <p class="text-muted fs-6">
                    Manage appointments, patients, and dental services in one modern and efficient system designed for better clinic operations.
                </p>

                <!-- CTA -->
                <div class="mt-4">
                    <a href="/login" class="btn btn-primary btn-lg px-4 me-2">
                        Get Started
                    </a>

                    <a href="/appointment" class="btn btn-outline-primary btn-lg px-4">
                        Book Appointment
                    </a>
                </div>

            </div>

            <div class="col-lg-6 text-center">
                <img src="/assets/images/bg.png" class="hero-img img-fluid">
            </div>

        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="container py-5">
    <h2 class="text-center fw-bold mb-5">Clinic Features</h2>

    <div class="row g-4">

        <div class="col-md-4">
            <?php featureCard("bi-calendar-check", "Appointments", "Manage patient schedules easily and efficiently."); ?>
        </div>

        <div class="col-md-4">
            <?php featureCard("bi-people", "Patient Records", "Secure and organized dental history management."); ?>
        </div>

        <div class="col-md-4">
            <?php featureCard("bi-star", "Feedback System", "Collect patient feedback to improve service quality."); ?>
        </div>

    </div>
</section>

<!-- ABOUT -->
<section class="container py-5">
    <div class="row align-items-center">

        <div class="col-lg-6">
            <h2 class="fw-bold">About ToothCare</h2>
            <p class="text-muted mt-3">
                ToothCare is a modern dental clinic management system designed to improve workflow efficiency,
                patient experience, and clinic operations.
            </p>
        </div>

        <div class="col-lg-6">
            <div class="row g-3">

                <div class="col-6">
                    <?php statBox("500+", "Patients"); ?>
                </div>

                <div class="col-6">
                    <?php statBox("10+", "Services"); ?>
                </div>

                <div class="col-6">
                    <?php statBox("5", "Dentists"); ?>
                </div>

                <div class="col-6">
                    <?php statBox("24/7", "System Access"); ?>
                </div>

            </div>
        </div>

    </div>
</section>

<?php include "../includes/footer_public.php"; ?>