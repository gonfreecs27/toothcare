<?php
require '../../../init.php';
Permission::authorize(['admin', 'staff', 'dentist']);

Core::loadModel("Patient");
$patientClass = new Patient();
$patients = $patientClass->all();

Component::header();
Component::sidebar();
?>

<div class="main-wrapper">

    <div class="content">

        <!-- PAGE HEADER -->
        <div class="dashboard-header mb-4">

            <div>
                <h3 class="fw-bold mb-1">Patient Management</h3>
                <p class="text-muted mb-0">Manage clinic patients and records</p>
            </div>

            <a href="<?= PROJECT_BASE ?>admin/patients/create" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add Patient
            </a>

        </div>

        <!-- TABLE CARD -->
        <div class="card border-0 shadow-sm dashboard-panel">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table align-middle small" id="patientsTable">

                        <thead>
                            <tr>
                                <th width="70">#</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Birthdate</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($patients as $row): ?>
                                <tr data-id="<?= $row['id'] ?>">

                                    <td><?= $row['id'] ?></td>

                                    <td>
                                        <div class="d-flex align-items-center gap-2">

                                            <div class="user-avatar-table">
                                                <i class="bi bi-person-fill"></i>
                                            </div>

                                            <div class="fw-semibold">
                                                <?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?>
                                            </div>

                                        </div>
                                    </td>

                                    <td><?= htmlspecialchars($row['gender'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($row['birthdate'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($row['contact'] ?? '-') ?></td>

                                    <!-- STATUS -->
                                    <td>
                                        <?php if (($row['status'] ?? 'active') === 'active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- ACTIONS -->
                                    <td>
                                        <div class="d-flex gap-2">

                                            <a href="<?= PROJECT_BASE ?>admin/patients/edit?id=<?= $row['id'] ?>"
                                                class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <button class="btn btn-danger btn-sm btn-delete-patient"
                                                data-id="<?= $row['id'] ?>"
                                                data-name="<?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>

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

<script>
    $(document).ready(function() {

        let table = $('#patientsTable').DataTable({
            responsive: true,
            pageLength: 10,
            ordering: true
        });

        $(document).on('click', '.btn-delete-patient', function() {

            let id = $(this).data('id');
            let name = $(this).data('name');
            let row = $(this).closest('tr');

            alertify.confirm(
                'Delete Patient',
                'Are you sure you want to delete <b>' + name + '</b>? This action cannot be undone.',
                function() {

                    $.ajax({
                        url: "<?= PROJECT_BASE ?>admin/patients/delete",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id: id
                        },

                        success: function(res) {

                            if (res.success) {

                                alertify.success(res.message || 'Patient deleted');

                                // remove from DataTable properly
                                table.row(row).remove().draw(false);

                            } else {
                                alertify.error(res.message || 'Failed to delete');
                            }
                        },

                        error: function(xhr) {
                            let msg = 'Server error';

                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            }

                            alertify.error(msg);
                        }
                    });

                },
                function() {
                    // Do nothing
                }
            ).set('labels', {
                ok: 'Delete',
                cancel: 'Cancel'
            });

        });

    });
</script>

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