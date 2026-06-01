<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? BRAND_NAME ?>: Your Dental Clinic System</title>

    <link rel="icon" href="<?= PROJECT_BASE ?>assets/images/icon.png" type="image/x-icon">

    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DATATABLES -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

    <!-- ALERTIFY -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css">

    <!-- FULL CALENDAR -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    <!-- SELECTIZE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/selectize/dist/css/selectize.default.css">
    <script src="https://cdn.jsdelivr.net/npm/selectize/dist/js/standalone/selectize.min.js"></script>

    <link rel="stylesheet" href="<?= PROJECT_BASE ?>assets/css/landing.css">
    <script src="<?= PROJECT_BASE ?>assets/js/app.js"></script>
    <?php
    if (isset($additional_css)) {
        foreach ($additional_css as $css) {
            echo '<link rel="stylesheet" href="' . $css . '">';
        }
    }

    if (isset($additional_js)) {
        foreach ($additional_js as $js) {
            echo '<script src="' . $js . '"></script>';
        }
    }

    ?>
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
        <div class="container">

            <!-- LOGO -->
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= PROJECT_BASE ?>">
                <span class="fw-bold fs-4 text-primary">
                    <?= BRAND_NAME_FIRST ?><span class="text-info"><?= BRAND_NAME_SECOND ?></span>
                </span>
            </a>

            <!-- MOBILE TOGGLE -->
            <button
                class="navbar-toggler border-0 shadow-none"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarContent"
                aria-controls="navbarContent"
                aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="bi bi-list fs-2"></i>
            </button>

            <!-- NAV CONTENT -->
            <div class="collapse navbar-collapse" id="navbarContent">

                <!-- CENTER MENU -->
                <ul class="navbar-nav mx-auto mb-3 mb-lg-0">

                    <li class="nav-item">
                        <a class="nav-link" href="<?= PROJECT_BASE ?>">
                            <i class="bi bi-house-door me-1"></i>
                            Home
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= PROJECT_BASE ?>services">
                            <i class="bi bi-grid me-1"></i>
                            Services
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= PROJECT_BASE ?>feedback">
                            <i class="bi bi-star me-1"></i>
                            Feedback
                        </a>
                    </li>

                </ul>

                <!-- ACTION BUTTONS -->
                <div class="d-flex flex-column flex-lg-row gap-2">
                    <a href="<?= PROJECT_BASE ?>login" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right me-1"></i>
                        Login
                    </a>

                </div>

            </div>

        </div>
    </nav>