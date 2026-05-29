<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /login.php");
    exit;
}

require_once(__DIR__ . '/../../../models/Dentist.php');

$dentistModel = new Dentist();

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $specialization = trim($_POST['specialization'] ?? '');
    $license_number = trim($_POST['license_number'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $status = $_POST['status'] ?? 'active';

    // ---------------- VALIDATION ----------------
    if ($name === '') $errors[] = "Full name is required.";
    if ($email === '') $errors[] = "Email is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";
    if ($password === '') $errors[] = "Password is required.";

    if ($firstname === '') $errors[] = "Firstname is required.";
    if ($lastname === '') $errors[] = "Lastname is required.";

    // ---------------- PROCESS ----------------
    if (empty($errors)) {
        try {

            $dentistModel->create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'specialization' => $specialization,
                'license_number' => $license_number,
                'contact' => $contact,
                'email' => $email,
                'status' => $status
            ]);

            $success = true;
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
    }
}

include __DIR__ . '/../../../includes/header_app.php';
include __DIR__ . '/../../../includes/sidebar.php';
?>

<div class="main-wrapper">
    <div class="content">

        <div class="dashboard-header mb-4">
            <div>
                <h3 class="fw-bold mb-1">Add Dentist</h3>
                <p class="text-muted mb-0">Create dentist account and profile</p>
            </div>

            <a href="/admin/dentists" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

        <!-- SUCCESS -->
        <?php if ($success): ?>
            <div class="alert alert-success">
                Dentist created successfully.
                <a href="/admin/dentists/create" class="alert-link">Add another</a>
                or
                <a href="/admin/dentists" class="alert-link">go back to list</a>.
            </div>
        <?php endif; ?>

        <!-- ERRORS -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-body">

                <form method="POST">

                    <div class="row g-3">

                        <!-- USER INFO -->
                        <div class="col-12">
                            <h5 class="fw-bold">Account Information</h5>
                            <hr>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <!-- DENTIST INFO -->
                        <div class="col-12 mt-3">
                            <h5 class="fw-bold">Dentist Profile</h5>
                            <hr>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Firstname</label>
                            <input type="text" name="firstname" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Lastname</label>
                            <input type="text" name="lastname" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Specialization</label>
                            <input type="text" name="specialization" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">License Number</label>
                            <input type="text" name="license_number" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Contact</label>
                            <input type="text" name="contact" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <!-- BUTTON -->
                        <div class="col-12 mt-3">
                            <button class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Save Dentist
                            </button>
                        </div>

                    </div>

                </form>

            </div>
        </div>

    </div>
    <?php include __DIR__ . '/../../../includes/footer_app.php'; ?>
</div>