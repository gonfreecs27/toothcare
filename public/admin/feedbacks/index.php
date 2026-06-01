<?php

require '../../../init.php';
Permission::authorize(['admin', 'staff']);

Core::loadModel("Feedback");

$feedbackModel = new Feedback();
$feedbacks = $feedbackModel->allWithLatestFirst();

Component::header(false, null, [
    PROJECT_BASE . 'assets/js/feedback.js'
]);
Component::sidebar();

?>

<div class="main-wrapper">
    <div class="content">
        <div class="dashboard-header mb-4">
            <div>
                <h3 class="fw-bold mb-1">Feedback Management</h3>
                <p class="text-muted mb-0">
                    Review, approve and feature patient feedback
                </p>
            </div>

        </div>

        <div class="card border-0 shadow-sm dashboard-panel">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table align-middle" id="feedbackTable">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Patient</th>
                                <th>Rating</th>
                                <th>Feedback</th>
                                <th>Submitted</th>
                                <th>Status</th>
                                <th>Featured</th>
                                <th width="220">Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($feedbacks as $row): ?>

                                <tr>

                                    <td><?= $row['id'] ?></td>

                                    <td>
                                        <div class="fw-semibold">
                                            <?= htmlspecialchars($row['name']) ?>
                                        </div>

                                        <?php if (!empty($row['email'])): ?>
                                            <small class="text-muted">
                                                <?= htmlspecialchars($row['email']) ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>

                                    <td>

                                        <span class="badge bg-warning text-dark">

                                            <i class="bi bi-star-fill"></i>

                                            <?= $row['rating'] ?>/5

                                        </span>

                                    </td>

                                    <td style="max-width:300px">

                                        <?= htmlspecialchars(mb_strimwidth(
                                            $row['message'],
                                            0,
                                            120,
                                            '...'
                                        )) ?>

                                    </td>

                                    <td>
                                        <?= date(
                                            'M d, Y',
                                            strtotime($row['created_at'])
                                        ) ?>
                                    </td>

                                    <td>

                                        <?php if ($row['status'] == 'approved'): ?>
                                            <span class="badge bg-success">
                                                Approved
                                            </span>
                                        <?php elseif ($row['status'] == 'rejected'): ?>
                                            <span class="badge bg-danger">
                                                Rejected
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">
                                                Pending
                                            </span>
                                        <?php endif; ?>

                                    </td>

                                    <td>

                                        <?php if ($row['is_featured']): ?>
                                            <span class="badge bg-primary">
                                                Featured
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-dark">
                                                No
                                            </span>
                                        <?php endif; ?>

                                    </td>

                                    <td>

                                        <div class="d-flex gap-2 flex-wrap">
                                            <button
                                                class="btn btn-info btn-sm btn-view"
                                                data-name="<?= htmlspecialchars($row['name']) ?>"
                                                data-email="<?= htmlspecialchars($row['email']) ?>"
                                                data-rating="<?= $row['rating'] ?>"
                                                data-message="<?= htmlspecialchars($row['message']) ?>"
                                                data-date="<?= date('M d, Y h:i A', strtotime($row['created_at'])) ?>">
                                                <i class="bi bi-info-circle"></i>
                                            </button>

                                            <button
                                                class="btn btn-success btn-sm btn-approve"
                                                data-id="<?= $row['id'] ?>">
                                                <i class="bi bi-check-circle"></i>
                                            </button>

                                            <button
                                                class="btn btn-danger btn-sm btn-reject"
                                                data-id="<?= $row['id'] ?>">
                                                <i class="bi bi-x-circle"></i>
                                            </button>

                                            <?php if (Permission::hasAccess(['admin'])): ?>
                                                <button
                                                    class="btn btn-warning btn-sm btn-feature"
                                                    data-id="<?= $row['id'] ?>">
                                                    <i class="bi bi-star"></i>
                                                </button>
                                            <?php endif; ?>

                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="feedbackModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Patient Feedback
                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <strong>Name:</strong>
                        <div id="feedbackName"></div>
                    </div>

                    <div class="mb-3">
                        <strong>Email:</strong>
                        <div id="feedbackEmail"></div>
                    </div>

                    <div class="mb-3">
                        <strong>Rating:</strong>
                        <div id="feedbackRating"></div>
                    </div>

                    <div class="mb-3">
                        <strong>Submitted:</strong>
                        <div id="feedbackDate"></div>
                    </div>

                    <div class="mb-3">
                        <strong>Feedback:</strong>
                        <div
                            id="feedbackMessage"
                            class="border rounded p-3 bg-light">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php Component::footer(); ?>

</div>