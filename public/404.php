<?php
require_once(__DIR__ . '/../init.php');
Component::header(true, "404 - Page Not Found | "  . BRAND_NAME);
?>

<!-- 404 CONTENT -->
<section class="d-flex align-items-center justify-content-center" style="min-height: 80vh;">

    <div class="text-center">

        <div style="font-size: 100px; font-weight: bold; color: #0d6efd;">
            404
        </div>

        <h3 class="fw-bold">Page Not Found</h3>

        <p class="text-muted">
            The page you're looking for doesn't exist or may have been moved.
        </p>

        <a href="<?= PROJECT_BASE ?>" class="btn btn-primary btn-lg mt-3">
            <i class="bi bi-house"></i> Back to Home
        </a>

    </div>

</section>

<?php Component::footer(true) ?>