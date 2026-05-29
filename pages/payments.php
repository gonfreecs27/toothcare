<?php
session_start();

require_once('../classes/database.php');

$db = new database();

if (isset($_GET['pay'])) {

    $db->markAsPaid($_GET['pay']);

    header("Location: payments.php");
    exit();
}

$payments = $db->getPayments();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payments</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Payment Management</h4>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-hover">

                <thead class="table-dark">
                    <tr>
                        <th>Patient Name</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($payments as $p): ?>

                        <tr>

                            <td><?= $p['patient_name'] ?></td>
                            <td>₱<?= $p['amount'] ?></td>
                            <td><?= $p['payment_method'] ?></td>

                            <td>
                                <?php if ($p['status'] == 'paid'): ?>
                                    <span class="badge bg-success">Paid</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                <?php endif; ?>
                            </td>

                            <td><?= $p['payment_date'] ?></td>

                            <td>
                                <?php if ($p['status'] == 'pending'): ?>
                                    <a href="?pay=<?= $p['payment_id'] ?>" class="btn btn-success btn-sm">
                                        Mark Paid
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm" disabled>Done</button>
                                <?php endif; ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>
      <div class="text-center mt-5">
    <a href="admin_dashboard.php" class="btn btn-outline-primary">Back</a>
  </div>

</div>

</body>
</html>