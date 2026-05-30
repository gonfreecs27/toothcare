<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? "ToothCare" ?></title>

    <link rel="icon" href="/assets/images/icon.png" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="/assets/css/landing.css">
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
        <div class="container">

            <!-- LOGO -->
            <a class="navbar-brand d-flex align-items-center gap-2" href="/">
                <span class="fw-bold fs-4 text-primary">
                    Tooth<span class="text-info">Care</span>
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
                        <a class="nav-link" href="/">
                            <i class="bi bi-house-door me-1"></i>
                            Home
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/services">
                            <i class="bi bi-grid me-1"></i>
                            Services
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/feedback">
                            <i class="bi bi-star me-1"></i>
                            Feedback
                        </a>
                    </li>

                </ul>

                <!-- ACTION BUTTONS -->
                <div class="d-flex flex-column flex-lg-row gap-2">
                    <a href="/login" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right me-1"></i>
                        Login
                    </a>

                </div>

            </div>

        </div>
    </nav>