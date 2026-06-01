<?php
require '../../../init.php';
Permission::authorize(['admin']);

Core::loadModel("User");
$userClass = new User();
$users = $userClass->all();

$totalUsers = count($users);
$admins = count(array_filter($users, fn($u) => $u['role'] === 'admin'));
$staff = count(array_filter($users, fn($u) => $u['role'] === 'staff'));
$dentists = count(array_filter($users, fn($u) => $u['role'] === 'dentist'));

Component::header();
Component::sidebar();
?>

<div class="main-wrapper">

    <div class="content">

        <!-- PAGE HEADER -->
        <div class="dashboard-header mb-4">

            <div>
                <h3 class="fw-bold mb-1">
                    User Management
                </h3>

                <p class="text-muted mb-0">
                    Manage administrators, staff, and dentists
                </p>

            </div>

            <a href="<?= PROJECT_BASE ?>admin/users/create"
                class="btn btn-primary">

                <i class="bi bi-plus-circle"></i>
                Add User
            </a>

        </div>

        <div class="row g-3 mb-4">
            <!-- Total Users -->
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Users</h6>
                            <h3 class="fw-bold mb-0"><?= $totalUsers ?></h3>
                        </div>
                        <div class="fs-1 text-primary">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admins -->
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Admins</h6>
                            <h3 class="fw-bold mb-0"><?= $admins ?></h3>
                        </div>
                        <div class="fs-1 text-danger">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Staff -->
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Staff</h6>
                            <h3 class="fw-bold mb-0"><?= $staff ?></h3>
                        </div>
                        <div class="fs-1 text-success">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dentists -->
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Dentists</h6>
                            <h3 class="fw-bold mb-0"><?= $dentists ?></h3>
                        </div>
                        <div class="fs-1 text-success">
                            <i class="bi bi-heart-pulse-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- CARD -->
        <div class="card border-0 shadow-sm dashboard-panel">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table align-middle" id="usersTable">

                        <thead>
                            <tr>
                                <th width="70">#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th width="150">Role</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($users as $row): ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>

                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="user-avatar-table">
                                                <i class="bi bi-person-fill"></i>
                                            </div>

                                            <div>
                                                <div class="fw-semibold">
                                                    <?= htmlspecialchars($row['name']) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td><?= htmlspecialchars($row['email']) ?></td>

                                    <td>
                                        <span class="badge-role badge-role-<?= $row['role'] ?>"><?= ucfirst($row['role']) ?></span>
                                    </td>

                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="<?= PROJECT_BASE ?>admin/users/edit?id=<?= $row['id'] ?>"
                                                class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <a href="#"
                                                class="btn btn-danger btn-sm btn-delete-user"
                                                data-id="<?= $row['id'] ?>"
                                                data-name="<?= htmlspecialchars($row['name']) ?>">
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

<script>
    $(document).ready(function() {
        $('#usersTable').DataTable({
            responsive: true,
            pageLength: 10,
            ordering: true
        });

        $('.btn-delete-user').on('click', function (e) {
            e.preventDefault();
            let userId = $(this).data('id');
            let userName = $(this).data('name');
            let deleteUrl = '<?= PROJECT_BASE ?>admin/users/delete?id=' + userId;

            alertify.confirm(
                'Delete User',
                'Are you sure you want to delete <b>' + userName + '</b>?',
                function () {
                    window.location.href = deleteUrl;
                },
                function () {
                    alertify.error('Cancelled');
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

    .badge-role {
        display: inline-block;
        padding: 7px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-role-admin {
        background: #e7f1ff;
        color: #0d6efd;
    }

    .badge-role-staff {
        background: #eafaf1;
        color: #198754;
    }

    .badge-role-dentist {
        background: #fff4e5;
        color: #ff9800;
    }

    table.dataTable thead th {
        border-bottom: none !important;
    }
</style>