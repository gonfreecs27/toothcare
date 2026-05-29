<?php
session_start();

require_once('../classes/database.php');

$con = new database();

/* ================= SECURITY (IMPORTANT) ================= */
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

/* ================= GET SERVICES ================= */
$services = $con->getServices();

/* ================= STATUS ================= */
$updateServiceStatus = '';
$updateServiceMessage = '';

/* ================= UPDATE SERVICE ================= */
if (isset($_POST['save_service'])) {

    try {

        $service_id   = (int) $_POST['service_id'];
        $service_name = trim($_POST['service_name']);
        $description  = trim($_POST['description']);
        $price        = (float) $_POST['price'];

        if ($service_id <= 0 || $service_name == '' || $price < 0) {
            throw new Exception("Invalid input data.");
        }

        $result = $con->updateService(
            $service_id,
            $service_name,
            $description,
            $price
        );

        if ($result) {
            $updateServiceStatus = 'success';
            $updateServiceMessage = 'Service updated successfully!';
        } else {
            throw new Exception("Failed to update service.");
        }

        // refresh data
        $services = $con->getServices();

    } catch (Exception $e) {
        $updateServiceStatus = 'error';
        $updateServiceMessage = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/landing.css">
</head>

<body>

<div class="container-fluid main-container">

    <div class="text-center mb-5">
        <h1 class="section-title">Our Dental Services</h1>
    </div>

    <div class="row g-4">

        <?php foreach ($services as $row): ?>

            <div class="col-md-4">

                <div class="feature-card shadow-sm text-center p-4">

                    <div class="feature-icon mb-2">
                        <i class="bi <?= htmlspecialchars($row['icon'] ?? 'bi-heart-pulse') ?>"></i>
                    </div>

                    <h4>
                        <?= htmlspecialchars($row['service_name']) ?>
                    </h4>

                    <p class="text-muted">
                        <?= htmlspecialchars($row['description']) ?>
                    </p>

                    <?php if (!empty($row['price'])): ?>
                        <div class="text-primary fw-bold">
                            ₱<?= number_format($row['price'], 2) ?>
                        </div>
                    <?php endif; ?>

                    <button class="btn btn-sm btn-primary mt-3"
                            data-bs-toggle="modal"
                            data-bs-target="#edit<?= $row['service_id'] ?>">
                        Edit
                    </button>

                </div>

            </div>

            <!-- MODAL -->
            <div class="modal fade" id="edit<?= $row['service_id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <form method="POST">

                            <div class="modal-header">
                                <h5 class="modal-title">Edit Service</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <input type="hidden"
                                       name="service_id"
                                       value="<?= (int)$row['service_id'] ?>">

                                <label>Service Name</label>
                                <input type="text"
                                       name="service_name"
                                       class="form-control mb-2"
                                       value="<?= htmlspecialchars($row['service_name']) ?>"
                                       required>

                                <label>Description</label>
                                <textarea name="description"
                                          class="form-control mb-2"
                                          required><?= htmlspecialchars($row['description']) ?></textarea>

                                <label>Price</label>
                                <input type="number"
                                       name="price"
                                       class="form-control mb-2"
                                       value="<?= htmlspecialchars($row['price'] ?? 0) ?>"
                                       min="0"
                                       required>

                            </div>

                            <div class="modal-footer">
                                <button type="submit"
                                        name="save_service"
                                        class="btn btn-primary w-100">
                                    Save Changes
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

        <?php endforeach; ?>

    </div>

    <div class="text-center mt-5">
        <a href="admin_dashboard.php" class="btn btn-outline-primary">Back</a>
    </div>

</div>

<!-- BOOTSTRAP -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- SWEETALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const status = <?= json_encode($updateServiceStatus) ?>;
const message = <?= json_encode($updateServiceMessage) ?>;

if (status === 'success') {
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: message
    });
} else if (status === 'error') {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message
    });
}
</script>

</body>
</html>