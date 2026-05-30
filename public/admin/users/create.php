<?php
require '../../../init.php';

if (!Permission::hasAccess(['admin'])) {
    Core::redirect("login");
}

Core::loadModel("User");
$userClass = new User();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role     = trim($_POST['role'] ?? '');

    if ($name && $email && $password && $role) {
        try {
            $created = $userClass->create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'role' => $role
            ]);

            if ($created) {
                Core::redirect("admin/users/");
            }
        } catch (PDOException $e) {
            // Duplicate email
            if ($e->getCode() == 23000) {
                $error = "Email is already taken. Please use another one.";
            } else {
                $error = "Database error occurred. Please try again.";
            }
        }
    } else {
        $error = "Please complete all required fields.";
    }
}

Component::header();
Component::sidebar();
?>

<div class="main-wrapper">

    <div class="content">

        <!-- HEADER -->
        <div class="dashboard-header mb-4">

            <div>

                <h3 class="fw-bold mb-1">
                    Create User
                </h3>

                <p class="text-muted mb-0">
                    Add a new system account (admin, staff, or dentist)
                </p>

            </div>

        </div>

        <!-- FORM CARD -->
        <div class="row justify-content-center">

            <div class="col-lg-7">

                <div class="card dashboard-panel border-0 shadow-sm">

                    <div class="card-body p-4">

                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                <?= $error ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST">

                            <div class="mb-3">

                                <label class="form-label">Full Name</label>

                                <input type="text"
                                    name="name"
                                    class="form-control"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">Email</label>

                                <input type="email"
                                    name="email"
                                    class="form-control"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">Password</label>

                                <input type="password"
                                    name="password"
                                    class="form-control"
                                    required>

                            </div>

                            <div class="mb-4">

                                <label class="form-label">Role</label>

                                <select name="role"
                                    class="form-select"
                                    required>

                                    <option value="">Select role</option>
                                    <option value="admin">Admin</option>
                                    <option value="staff">Staff</option>
                                </select>

                            </div>

                            <div class="d-flex gap-2">

                                <button class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i>
                                    Save User
                                </button>

                                <a href="<?= PROJECT_BASE ?>admin/users/" class="btn btn-light">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php Component::footer();?>
</div>