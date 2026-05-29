<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /login");
    exit;
}

require_once(__DIR__ . '/../../../models/User.php');

$userClass = new User();

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: /admin/users/");
    exit;
}

$data = $userClass->find($id);

if (!$data) {
    header("Location: /admin/users/");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role     = trim($_POST['role'] ?? '');

    try {
        $updated = $userClass->update($id, [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role
        ]);

        if ($updated) {
            header("Location: /admin/users/");
            exit;
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $error = "Email is already taken.";
        } else {
            $error = "Database error occurred. Please try again.";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

include __DIR__ . '/../../../includes/header_app.php';
include __DIR__ . '/../../../includes/sidebar.php';
?>

<div class="main-wrapper">
    <div class="content">
        <div class="dashboard-header mb-4">
            <div>
                <h3 class="fw-bold mb-1">
                    Edit User
                </h3>

                <p class="text-muted mb-0">
                    Update user account information
                </p>
            </div>
        </div>

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
                                    value="<?= htmlspecialchars($data['name']) ?>"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">Email</label>

                                <input type="email"
                                    name="email"
                                    class="form-control"
                                    value="<?= htmlspecialchars($data['email']) ?>"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">Password</label>

                                <input type="password"
                                    name="password"
                                    class="form-control">

                                <small class="text-muted">
                                    Leave blank to keep current password
                                </small>

                            </div>

                            <div class="mb-4">

                                <label class="form-label">Role</label>

                                <select name="role" class="form-select" required>

                                    <option value="admin" <?= $data['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                    <option value="staff" <?= $data['role'] === 'staff' ? 'selected' : '' ?>>Staff</option>
                                    <option value="dentist" <?= $data['role'] === 'dentist' ? 'selected' : '' ?>>Dentist</option>

                                </select>

                            </div>

                            <div class="d-flex gap-2">

                                <button class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i>
                                    Update User
                                </button>

                                <a href="/admin/users/"
                                    class="btn btn-light">

                                    Cancel

                                </a>

                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../../../includes/footer_app.php'; ?>

</div>