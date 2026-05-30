<?php
$user = $_SESSION['user'] ?? null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>ToothCare System</title>

    <link rel="icon" href="/assets/images/icon.png">

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

    <link rel="stylesheet" href="/assets/css/layout.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    
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