<?php
require '../../../init.php';
Permission::authorize(['admin', 'staff', 'dentist']);

Core::loadModel("Patient");
$patientClass = new Patient();

$id = $_GET['id'] ?? null;

if (!$id) {
    Core::redirect("admin/patients/");
}

$patient = $patientClass->find($id);

if (!$patient) {
    Core::redirect("admin/patients/");
}

$errors = [];

// flash message
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// form state
$form = [
    'firstname' => $patient['firstname'],
    'lastname' => $patient['lastname'],
    'birthdate' => $patient['birthdate'],
    'gender' => $patient['gender'],
    'contact' => $patient['contact'],
    'email' => $patient['email'],
    'address' => $patient['address'],
    'civil_status' => $patient['civil_status'],
    'status' => $patient['status']
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    foreach ($form as $key => $value) {
        $form[$key] = trim($_POST[$key] ?? $value);
    }

    // validation
    if ($form['firstname'] === '') $errors['firstname'] = "First name is required.";
    if ($form['lastname'] === '') $errors['lastname'] = "Last name is required.";
    if ($form['email'] === '') $errors['email'] = "Email is required.";

    if (!empty($form['email']) && !filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    if (empty($errors)) {
        try {
            $patientClass->update($id, $form);

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Patient updated successfully!'
            ];

            Core::redirect("admin/patients/edit?id=" . $id);
            exit;
        } catch (Exception $e) {
            $errors['global'] = $e->getMessage();
        }
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
                <h3 class="fw-bold mb-1">Edit Patient</h3>
                <p class="text-muted mb-0">Update patient information</p>
            </div>

            <a href="<?= PROJECT_BASE ?>admin/patients" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

        <!-- FLASH MESSAGE -->
        <?php if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>">
                <?= $flash['message'] ?>
            </div>
        <?php endif; ?>

        <!-- GLOBAL ERROR -->
        <?php if (!empty($errors['global'])): ?>
            <div class="alert alert-danger">
                <?= $errors['global'] ?>
            </div>
        <?php endif; ?>

        <!-- FORM -->
        <div class="card border-0 shadow-sm dashboard-panel">
            <div class="card-body">

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
                                <div class="invalid-feedback"><?= $errors['firstname'] ?></div>
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
                                <div class="invalid-feedback"><?= $errors['lastname'] ?></div>
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
                            <label class="form-label">Email *</label>
                            <input type="email"
                                name="email"
                                class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                                value="<?= htmlspecialchars($form['email']) ?>">

                            <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback"><?= $errors['email'] ?></div>
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

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Patient
                        </button>

                        <a href="<?= PROJECT_BASE ?>admin/patients" class="btn btn-light">
                            Cancel
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>

    <?php Component::footer(); ?>
</div>