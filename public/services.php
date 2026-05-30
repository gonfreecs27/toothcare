<?php
require_once(__DIR__ . '/../init.php');

Component::header(true);
Core::loadModel("Service");

$serviceModel = new Service();
$services = $serviceModel->all();
?>

<!-- HERO -->
<section class="services-hero">

    <div class="container text-center">
        <div class="services-badge">
            <i class="bi bi-heart-pulse-fill"></i>
            Professional Dental Care
        </div>

        <h1 class="fw-bold display-5 mt-3">
            Our Dental Services
        </h1>

        <p class="text-muted fs-5">
            Comprehensive dental treatments designed to keep your smile
            healthy, beautiful, and confident.
        </p>
    </div>

</section>

<!-- SERVICES -->
<section class="container py-5">

    <div class="row g-4">

        <?php foreach ($services as $service): ?>

            <div class="col-lg-4 col-md-6">

                <div class="card service-card h-100">

                    <div class="card-body d-flex flex-column">

                        <h5 class="fw-bold mb-3">
                            <?= htmlspecialchars($service['name']) ?>
                        </h5>

                        <p class="text-muted flex-grow-1">
                            <?= !empty($service['description'])
                                ? htmlspecialchars($service['description'])
                                : 'Professional dental service provided by our experienced dental team.' ?>
                        </p>

                        <div class="service-price">
                            ₱<?= number_format($service['price'], 2) ?>
                        </div>

                        <div class="d-grid mt-3">
                            <a href="<?= PROJECT_BASE ?>appointment"
                                class="btn btn-primary">
                                <i class="bi bi-calendar-check"></i>
                                Book Appointment
                            </a>
                        </div>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

</section>

<!-- WHY CHOOSE US -->
<section class="why-us-section">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">
                Why Choose <?= BRAND_NAME ?>?
            </h2>

            <p class="text-muted">
                We are committed to providing exceptional dental care and patient comfort.
            </p>

        </div>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="feature-orange">
                    <?php Component::featureCard(
                        "bi-award",
                        "Experienced Dentists",
                        "Skilled professionals dedicated to quality treatment."
                    ); ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-green">
                    <?php Component::featureCard(
                        "bi-building",
                        "Modern Facility",
                        "State-of-the-art equipment for advanced dental care."
                    ); ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-blue">
                    <?php Component::featureCard(
                        "bi-emoji-smile",
                        "Patient Comfort",
                        "Friendly environment focused on your comfort and care."
                    ); ?>
                </div>
                </div>

        </div>

    </div>

</section>

<!-- CTA -->
<section class="services-cta">

    <div class="container text-center">

        <h2 class="fw-bold">
            Ready to Schedule Your Visit?
        </h2>

        <p class="text-muted mb-4">
            Book an appointment today and let us help you achieve a healthier smile.
        </p>

        <a href="<?= PROJECT_BASE ?>appointment" class="btn btn-primary btn-lg px-5">
            <i class="bi bi-calendar-check"></i>
            Book Appointment
        </a>

    </div>

</section>

<?php Component::footer(true) ?>