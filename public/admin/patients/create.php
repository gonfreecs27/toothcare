<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /login.php");
    exit;
}

require_once(__DIR__ . '/../../../models/Patient.php');

$patientClass = new Patient();

$errors = [];
$success = false;

// default values (for persistence on error)
$form = [
    'firstname' => '',
    'lastname' => '',
    'birthdate' => '',
    'gender' => '',
    'contact' => '',
    'email' => '',
    'address' => '',
    'civil_status' => '',
    'status' => 'active'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // collect input
    foreach ($form as $key => $value) {
        $form[$key] = trim($_POST[$key] ?? $value);
    }

    // validation
    if ($form['firstname'] === '') $errors['firstname'] = "First name is required.";
    if ($form['lastname'] === '') $errors['lastname'] = "Last name is required.";

    if (!empty($form['email']) && !filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    // if no errors → insert
    if (empty($errors)) {
        try {
            $patientClass->create($form);
            $success = true;
        } catch (Exception $e) {
            $errors['global'] = $e->getMessage();
        }
    }
}

include __DIR__ . '/../../../includes/header_app.php';
include __DIR__ . '/../../../includes/sidebar.php';
?>

<div class="main-wrapper">
    <div class="content">

        <!-- HEADER -->
        <div class="dashboard-header mb-4">
            <div>
                <h3 class="fw-bold mb-1">Add Patient</h3>
                <p class="text-muted mb-0">Create a new patient record</p>
            </div>

            <?php if (!$success): ?>
                <a href="/admin/patients" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            <?php endif; ?>
        </div>

        <!-- SUCCESS STATE -->
        <?php if ($success): ?>

            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">

                    <div class="mb-3 text-success fs-1">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>

                    <h4 class="fw-bold">Patient Created Successfully</h4>
                    <p class="text-muted">The record has been saved to the system.</p>

                    <div class="d-flex justify-content-center gap-2 mt-4">

                        <a href="/admin/patients/create" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Add New Patient
                        </a>

                        <a href="/admin/patients" class="btn btn-light">
                            Back to List
                        </a>

                    </div>

                </div>
            </div>

        <?php else: ?>

            <!-- FORM CARD -->
            <div class="card border-0 shadow-sm dashboard-panel">
                <div class="card-body">

                    <!-- GLOBAL ERROR -->
                    <?php if (!empty($errors['global'])): ?>
                        <div class="alert alert-danger">
                            <?= $errors['global'] ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">

                        <div class="row g-3">

                            <!-- FIRST NAME -->
                            <div class="col-md-6">
                                <label class="form-label">First Name *</label>
                                <input type="text"
                                    name="firstname"
                                    class="form-control <?= isset($errors['firstname']) ? 'is-invalid' : '' ?>"
                                    value="<?= htmlspecialchars($form['firstname']) ?>">

                                <?php if (isset($errors['firstname'])): ?>
                                    <div class="invalid-feedback">
                                        <?= $errors['firstname'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- LAST NAME -->
                            <div class="col-md-6">
                                <label class="form-label">Last Name *</label>
                                <input type="text"
                                    name="lastname"
                                    class="form-control <?= isset($errors['lastname']) ? 'is-invalid' : '' ?>"
                                    value="<?= htmlspecialchars($form['lastname']) ?>">

                                <?php if (isset($errors['lastname'])): ?>
                                    <div class="invalid-feedback">
                                        <?= $errors['lastname'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- BIRTHDATE -->
                            <div class="col-md-4">
                                <label class="form-label">Birthdate</label>
                                <input type="date"
                                    name="birthdate"
                                    class="form-control"
                                    value="<?= htmlspecialchars($form['birthdate']) ?>">
                            </div>

                            <!-- GENDER -->
                            <div class="col-md-4">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="">-- Select --</option>
                                    <option value="male" <?= $form['gender'] === 'male' ? 'selected' : '' ?>>Male</option>
                                    <option value="female" <?= $form['gender'] === 'female' ? 'selected' : '' ?>>Female</option>
                                    <option value="other" <?= $form['gender'] === 'other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>

                            <!-- CIVIL STATUS -->
                            <div class="col-md-4">
                                <label class="form-label">Civil Status</label>
                                <select name="civil_status" class="form-select">
                                    <option value="">-- Select --</option>
                                    <option value="single" <?= $form['civil_status'] === 'single' ? 'selected' : '' ?>>Single</option>
                                    <option value="married" <?= $form['civil_status'] === 'married' ? 'selected' : '' ?>>Married</option>
                                    <option value="widowed" <?= $form['civil_status'] === 'widowed' ? 'selected' : '' ?>>Widowed</option>
                                    <option value="separated" <?= $form['civil_status'] === 'separated' ? 'selected' : '' ?>>Separated</option>
                                </select>
                            </div>

                            <!-- CONTACT -->
                            <div class="col-md-6">
                                <label class="form-label">Contact</label>
                                <input type="text"
                                    name="contact"
                                    class="form-control"
                                    value="<?= htmlspecialchars($form['contact']) ?>">
                            </div>

                            <!-- EMAIL -->
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email"
                                    name="email"
                                    class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                                    value="<?= htmlspecialchars($form['email']) ?>">

                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback">
                                        <?= $errors['email'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- ADDRESS -->
                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <textarea name="address" rows="3" class="form-control"><?= htmlspecialchars($form['address']) ?></textarea>
                            </div>

                            <!-- STATUS -->
                            <div class="col-md-4">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active" <?= $form['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="inactive" <?= $form['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                </select>
                            </div>

                        </div>

                        <!-- BUTTONS -->
                        <div class="mt-4 d-flex gap-2">

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Patient
                            </button>

                            <a href="/admin/patients" class="btn btn-light">
                                Cancel
                            </a>

                        </div>

                    </form>

                </div>
            </div>

        <?php endif; ?>

    </div>

    <?php include __DIR__ . '/../../../includes/footer_app.php'; ?>
</div>