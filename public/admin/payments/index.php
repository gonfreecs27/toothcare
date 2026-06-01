<?php
require '../../../init.php';

Permission::authorize(['admin', 'staff']);

Core::loadModel("Payment");

$user = $_SESSION['user'];

$paymentModel = new Payment();

// =========================
// STATS
// =========================
$totalRevenue = $paymentModel->totalRevenue();
$todayRevenue = $paymentModel->todayRevenue();
$monthlyRevenue = $paymentModel->monthlyRevenue();
$totalPayments = $paymentModel->countPayments();

$payments = $paymentModel->getPaymentsWithDetails();

Component::header();
Component::sidebar();
?>

<div class="main-wrapper">
    <div class="content">

        <!-- HEADER -->
        <div class="dashboard-header mb-4">

            <div>
                <h3 class="fw-bold mb-1">
                    Payment Overview
                </h3>

                <p class="text-muted mb-0">
                    Monitor clinic revenue and transactions.
                </p>
            </div>

            <div class="dashboard-date">
                <i class="bi bi-cash-coin"></i>
                Financial Records
            </div>

        </div>

        <!-- STATS -->
        <div class="row g-4">

            <div class="col-lg-3 col-md-6">

                <div class="dashboard-card card-revenue">

                    <div class="card-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>

                    <div class="card-details">
                        <span class="card-title">Total Revenue</span>

                        <h2 class="card-value">
                            <?= number_format($totalRevenue, 2) ?>
                        </h2>

                        <small class="text-muted">
                            All-time earnings
                        </small>
                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="dashboard-card card-appointments">

                    <div class="card-icon">
                        <i class="bi bi-calendar-day"></i>
                    </div>

                    <div class="card-details">
                        <span class="card-title">Today</span>

                        <h2 class="card-value">
                            <?= number_format($todayRevenue, 2) ?>
                        </h2>

                        <small class="text-muted">
                            Today's income
                        </small>
                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="dashboard-card card-patients">

                    <div class="card-icon">
                        <i class="bi bi-calendar-month"></i>
                    </div>

                    <div class="card-details">
                        <span class="card-title">This Month</span>

                        <h2 class="card-value">
                            <?= number_format($monthlyRevenue, 2) ?>
                        </h2>

                        <small class="text-muted">
                            Monthly revenue
                        </small>
                    </div>
                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="dashboard-card card-dentists">

                    <div class="card-icon">
                        <i class="bi bi-receipt"></i>
                    </div>

                    <div class="card-details">
                        <span class="card-title">Transactions</span>

                        <h2 class="card-value">
                            <?= number_format($totalPayments) ?>
                        </h2>

                        <small class="text-muted">
                            Total payments recorded
                        </small>
                    </div>

                </div>

            </div>

        </div>

        <!-- TABLE -->
        <div class="row mt-4">

            <div class="col-12">

                <div class="card shadow-sm border-0">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div>
                                <h5 class="fw-bold mb-1">
                                    Payment Records
                                </h5>

                                <p class="text-muted small mb-0">
                                    Latest transactions from appointments
                                </p>
                            </div>

                        </div>

                        <?php if (empty($payments)): ?>

                            <div class="text-center py-5">

                                <i class="bi bi-receipt fs-1 text-muted"></i>

                                <p class="text-muted mt-3 mb-0">
                                    No payment records found.
                                </p>

                            </div>

                        <?php else: ?>

                            <div class="table-responsive">

                                <table id="paymentTable" class="table align-middle small">

                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Patient</th>
                                            <th>Dentist</th>
                                            <th>Amount</th>
                                            <th>Method</th>
                                            <th>Reference</th>
                                            <th>Date Paid</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <?php foreach ($payments as $p): ?>

                                            <tr>
                                                <td>#<?= $p['id'] ?></td>

                                                <td>
                                                    <i class="bi bi-person-circle me-1"></i>
                                                    <?= htmlspecialchars($p['patient_name']) ?>
                                                </td>

                                                <td>
                                                    <i class="bi bi-person-badge me-1"></i>
                                                    <?= htmlspecialchars($p['dentist_name']) ?>
                                                </td>

                                                <td class="fw-bold text-success text-end">
                                                    <?= number_format($p['amount'], 2) ?>
                                                </td>

                                                <td>
                                                    <span class="badge bg-secondary">
                                                        <?= $p['payment_method'] ?? 'cash' ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?= $p['reference_no'] ?? '-' ?>
                                                </td>
                                                <td>
                                                    <?= date('M d, Y h:i A', strtotime($p['paid_at'])) ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $status = strtolower($p['payment_status'] ?? 'pending');

                                                    $badgeClass = [
                                                        'paid' => 'bg-success',
                                                        'pending' => 'bg-warning text-dark',
                                                        'unpaid' => 'bg-danger',
                                                        'failed' => 'bg-danger'
                                                    ][$status] ?? 'bg-secondary';
                                                    ?>

                                                    <span class="badge <?= $badgeClass ?>">
                                                        <?= ucfirst($status) ?>
                                                    </span>
                                                </td>
                                            </tr>

                                        <?php endforeach; ?>

                                    </tbody>

                                </table>

                            </div>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <?php Component::footer(); ?>

</div>

<script>
    $(document).ready(function() {
        $('#paymentTable').DataTable({
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            order: [
                [6, 'desc']
            ],
            responsive: true,
            language: {
                search: "",
                searchPlaceholder: "Search payments...",
                lengthMenu: "Show _MENU_ records",
                info: "Showing _START_ to _END_ of _TOTAL_ payments",
                paginate: {
                    previous: "Prev",
                    next: "Next"
                }
            }
        });
    });
</script>