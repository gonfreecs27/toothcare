<?php
require_once(__DIR__ . '/../includes/header_public.php');
require_once(__DIR__ . '/../includes/components.php');
?>

<!-- HERO -->
<section class="feedback-hero">

    <div class="container text-center">

        <div class="feedback-badge">
            <i class="bi bi-chat-square-heart-fill"></i>
            We Value Your Experience
        </div>

        <h1 class="fw-bold display-5 mt-3">
            Patient Feedback
        </h1>

        <p class="text-muted fs-5">
            Your feedback helps us improve our dental care services and patient experience.
        </p>

    </div>

</section>

<!-- FEEDBACK FORM -->
<section class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-7">

            <div class="card feedback-card">

                <div class="card-body p-4">

                    <h4 class="fw-bold mb-4">
                        Share Your Experience
                    </h4>

                    <form method="POST" action="/feedback/submit">

                        <!-- Name -->
                        <div class="mb-3">
                            <label class="form-label">Your Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter your name" required>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label">Email (optional)</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter your email">
                        </div>

                        <!-- Rating -->
                        <div class="mb-3">
                            <label class="form-label">Rating</label>

                            <div class="rating-group">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" name="rating" value="<?= $i ?>" id="star<?= $i ?>" required>
                                    <label for="star<?= $i ?>">
                                        <i class="bi bi-star-fill"></i>
                                    </label>
                                <?php endfor; ?>
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="mb-3">
                            <label class="form-label">Feedback</label>
                            <textarea name="message" class="form-control" rows="5"
                                placeholder="Tell us about your experience..." required></textarea>
                        </div>

                        <!-- Submit -->
                        <button class="btn btn-primary w-100">
                            <i class="bi bi-send"></i>
                            Submit Feedback
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- WHY FEEDBACK MATTERS -->
<section class="feedback-info">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">
                Why Your Feedback Matters
            </h2>

            <p class="text-muted">
                We continuously improve based on patient experiences.
            </p>

        </div>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="feature-orange">
                    <?php featureCard(
                        "bi-graph-up",
                        "Service Improvement",
                        "Helps us improve our dental services and patient care."
                    ); ?>
                </div>

            </div>

            <div class="col-md-4">
                <div class="feature-green">
                    <?php featureCard(
                        "bi-people",
                        "Patient Experience",
                        "Ensures a better experience for all our patients."
                    ); ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-blue">
                    <?php featureCard(
                        "bi-heart-pulse",
                        "Community Impact",
                        "Contributes to the overall health and well-being of our community."
                    ); ?>
                </div>
            </div>
        </div>

    </div>

</section>

<?php require_once(__DIR__ . '/../includes/footer_public.php'); ?>