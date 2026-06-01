<?php
require '../../../init.php';
Permission::authorize(['admin']);

Core::loadModel("Dentist");

$dentistClass = new Dentist();
$dentists = $dentistClass->all();

$totalDentists = count($dentists);
$activeDentists = count(array_filter($dentists, fn($d) => $d['status'] === 'active'));
$inactiveDentists = $totalDentists - $activeDentists;

Component::header();
Component::sidebar();
?>

<div class="main-wrapper">

    <div class="content">

        <!-- HEADER -->
        <div class="dashboard-header mb-4">

            <div>
                <h3 class="fw-bold mb-1">Dentist Management</h3>
                <p class="text-muted mb-0">Manage clinic dentists and credentials</p>
            </div>

            <a href="<?= PROJECT_BASE ?>admin/dentists/create" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add Dentist
            </a>

        </div>

        <!-- KPI CARDS -->
        <div class="row g-3 mb-4">

            <!-- Total -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Dentists</h6>
                            <h3 class="fw-bold mb-0"><?= $totalDentists ?></h3>
                        </div>
                        <div class="fs-1 text-primary">
                            <i class="bi bi-person-badge"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Active</h6>
                            <h3 class="fw-bold mb-0"><?= $activeDentists ?></h3>
                        </div>
                        <div class="fs-1 text-success">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inactive -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Inactive</h6>
                            <h3 class="fw-bold mb-0"><?= $inactiveDentists ?></h3>
                        </div>
                        <div class="fs-1 text-secondary">
                            <i class="bi bi-slash-circle"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- TABLE -->
        <div class="card border-0 shadow-sm dashboard-panel">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table align-middle" id="dentistsTable">

                        <thead>
                            <tr>
                                <th width="70">#</th>
                                <th>Name</th>
                                <th>Specialization</th>
                                <th>License</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th width="140">Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($dentists as $row): ?>
                                <tr>

                                    <td><?= $row['id'] ?></td>

                                    <!-- NAME -->
                                    <td>
                                        <div class="d-flex align-items-center gap-2">

                                            <div class="user-avatar-table">
                                                <i class="bi bi-person-badge"></i>
                                            </div>

                                            <div class="fw-semibold">
                                                <?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?>
                                            </div>

                                        </div>
                                    </td>

                                    <!-- SPECIALIZATION -->
                                    <td>
                                        <?= htmlspecialchars($row['specialization'] ?? '-') ?>
                                    </td>

                                    <!-- LICENSE -->
                                    <td>
                                        <?= htmlspecialchars($row['license_number'] ?? '-') ?>
                                    </td>

                                    <!-- CONTACT -->
                                    <td>
                                        <?= htmlspecialchars($row['contact'] ?? '-') ?>
                                    </td>

                                    <!-- STATUS -->
                                    <td>
                                        <?php if ($row['status'] === 'active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- ACTIONS -->
                                    <td>
                                        <div class="d-flex gap-2">

                                            <a href="<?= PROJECT_BASE ?>admin/dentists/edit?id=<?= $row['id'] ?>"
                                                class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <a href="#"
                                                class="btn btn-danger btn-sm btn-delete-dentist"
                                                data-id="<?= $row['id'] ?>"
                                                data-name="<?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?>">
                                                <i class="bi bi-trash"></i>
                                            </a>

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

    <?php Component::footer(); ?>

</div>

<!-- JS -->
<script>
    $(document).ready(function() {
        $('#dentistsTable').DataTable({
            responsive: true,
            pageLength: 10,
            ordering: true
        });

        $(document).on('click', '.btn-delete-dentist', function(e) {
            e.preventDefault();

            let id = $(this).data('id');
            let name = $(this).data('name');

            alertify.confirm(
                'Delete Dentist',
                'Are you sure you want to delete <b>' + name + '</b>?',
                function() {
                    window.location.href = '<?= PROJECT_BASE ?>admin/dentists/delete?id=' + id;
                },
                function() { }
            ).set('labels', {
                ok: 'Delete',
                cancel: 'Cancel'
            });
        });

    });
</script>

<!-- STYLE -->
<style>
    .user-avatar-table {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        background: #eef4ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    table.dataTable thead th {
        border-bottom: none !important;
    }

    .badge {
        font-size: 12px;
        padding: 6px 10px;
        border-radius: 20px;
    }
</style>