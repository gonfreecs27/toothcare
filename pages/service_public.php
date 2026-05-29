<?php
session_start();

require_once('../classes/database.php');
$con = new database();
$conn = $con->opencon();

// GET SERVICES FROM YOUR REAL TABLE
$services = $con->getServices();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dental Clinic - Services</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- YOUR STYLE -->
  <link rel="stylesheet" href="../assets/css/landing.css">

</head>

<body>

<div class="container-fluid main-container">

  <!-- HEADER -->
  <div class="text-center mb-5">
    <h1 class="section-title">Our Dental Services</h1>
    <p class="text-muted">Available services in the clinic</p>
  </div>

  <!-- SERVICES GRID -->
  <div class="row g-4">

    <?php foreach ($services as $row) { ?>

      <div class="col-md-4">

        <div class="feature-card shadow-sm text-center p-4">

          <!-- ICON -->
          <div class="feature-icon mb-2">
            <i class="bi <?= $row['icon'] ?? 'bi-heart-pulse' ?>"></i>
          </div>

          <!-- SERVICE NAME -->
          <h4>
            <?= htmlspecialchars($row['service_name']) ?>
          </h4>

          <!-- DESCRIPTION -->
          <p class="text-muted">
            <?= htmlspecialchars($row['description']) ?>
          </p>

          <!-- PRICE -->
          <?php if (!empty($row['price'])) { ?>
            <div class="text-primary fw-bold">
              ₱<?= number_format($row['price'], 2) ?>
            </div>
          <?php } ?>

        </div>

      </div>

    <?php } ?>

  </div>

  <!-- BACK BUTTON -->
  <div class="text-center mt-5">
    <a href="landingpages.php" class="btn btn-outline-primary">
      Back 
    </a>
  </div>

</div>

</body>
</html>