<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Clinic System</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/landing.css">

    <style>
        body {
            background: #f8fafc;
        }

        /* NAVBAR */
        .navbar {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        /* HERO */
        .hero {
            background: linear-gradient(135deg, #eef5ff, #ffffff);
            padding: 80px 0;
        }

        .hero-img {
            max-width: 420px;
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        /* FEATURES */
        .feature-card {
            background: #fff;
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: 0.3s;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            width: 55px;
            height: 55px;
            background: #e8f1ff;
            color: #0d6efd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            border-radius: 50%;
            margin: 0 auto 15px;
        }

        /* STATS */
        .stat-box {
            background: #fff;
            padding: 25px;
            border-radius: 14px;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        }

        /* CTA */
        .cta {
            background: #0d6efd;
            color: #fff;
            border-radius: 20px;
            padding: 60px;
        }

        footer {
            background: #fff;
            padding: 20px;
            text-align: center;
            margin-top: 50px;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">
                🦷 Dental Clinic System
            </a>

            <div class="ms-auto d-flex gap-2">
                <a href="service_public.php" class="btn btn-outline-primary btn-sm">Services</a>
                <a href="admin_login.php" class="btn btn-primary btn-sm">Login</a>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-6">
                    <span class="badge bg-primary-subtle text-primary mb-3 px-3 py-2">
                        Modern Dental Management System
                    </span>

                    <h1 class="display-5 fw-bold">
                        Smarter <span class="text-primary">Dental Clinic</span> Operations
                    </h1>

                    <p class="text-muted fs-5 mt-3">
                        Manage appointments, patients, dental records, and services in one centralized system designed for modern clinics.
                    </p>

                    <div class="mt-4 d-flex gap-3">
                        <a href="admin_login.php" class="btn btn-primary btn-lg px-4">
                            Get Started
                        </a>
                        <a href="online_appointment.php" class="btn btn-outline-primary btn-lg px-4">
                            Book Appointment
                        </a>
                    </div>
                </div>

                <div class="col-lg-6 text-center">
                    <img src="../assets/images/bg.png" class="hero-img img-fluid">
                </div>

            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="container py-5">
        <h2 class="text-center fw-bold mb-5">Clinic Features</h2>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <h5>Appointment Scheduling</h5>
                    <p class="text-muted">Easily manage patient bookings and schedules in real-time.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <h5>Patient Management</h5>
                    <p class="text-muted">Secure storage of patient records and dental history.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-star"></i>
                    </div>
                    <h5>Feedback System</h5>
                    <p class="text-muted">Collect and manage patient feedback for better service.</p>
                </div>
            </div>

        </div>
    </section>

    <!-- ABOUT + STATS -->
    <section class="container py-5">
        <div class="row align-items-center">

            <div class="col-lg-6">
                <h2 class="fw-bold">About Our Clinic</h2>
                <p class="text-muted mt-3">
                    We provide professional dental care services including cleaning, extraction, braces, whitening, and more.
                    Our system ensures efficient clinic operations and better patient experience.
                </p>
            </div>

            <div class="col-lg-6">
                <div class="row g-3">

                    <div class="col-6">
                        <div class="stat-box">
                            <h3 class="text-primary">500+</h3>
                            <p class="mb-0">Patients</p>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="stat-box">
                            <h3 class="text-primary">10+</h3>
                            <p class="mb-0">Services</p>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="stat-box">
                            <h3 class="text-primary">5</h3>
                            <p class="mb-0">Dentists</p>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="stat-box">
                            <h3 class="text-primary">24/7</h3>
                            <p class="mb-0">System Access</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <!-- CTA -->
    <section class="container py-5">
        <div class="cta text-center">
            <h2 class="fw-bold">Ready to modernize your dental clinic?</h2>
            <p class="mt-2">Start managing patients and appointments more efficiently today.</p>

            <a href="admin_login.php" class="btn btn-light btn-lg mt-3 px-4">
                Login Now
            </a>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <p class="mb-0">
            © <?php echo date("Y"); ?> Dental Clinic Management System
        </p>
    </footer>

</body>

</html>