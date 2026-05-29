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

        <a class="navbar-brand fw-bold text-primary" href="/">
            <h3 class="fw-bold text-primary">Tooth<span class="text-info">Care</span></h3>
        </a>

        <div class="ms-auto d-flex gap-2">
            <a href="services" class="btn btn-outline-primary btn-sm">
                Services
            </a>
            <a href="login" class="btn btn-primary btn-sm">
                Login
            </a>
        </div>

    </div>
</nav>