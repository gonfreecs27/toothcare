<?php
require '../../../init.php';

if (!Permission::hasAccess(['admin'])) {
    Core::redirect("login");
}

Core::loadModel("Dentist");
$dentistModel = new Dentist();

$id = $_GET['id'] ?? null;

if (!$id) {
    Core::redirect("admin/dentists/");
}

$errors = [];
$dentist = $dentistModel->find($id);
if (!$dentist) {
    Core::redirect("admin/dentists/");
}

// flash message
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$form = [
    'firstname' => $dentist['firstname'],
    'lastname' => $dentist['lastname'],
    'specialization' => $dentist['specialization'],
    'license_number' => $dentist['license_number'],
    'contact' => $dentist['contact'],
    'status' => $dentist['status']
];

// ---------------- UPDATE ----------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($form as $key => $value) {
        $form[$key] = trim($_POST[$key] ?? $value);
    }

    try {
        $dentistModel->update($id, $form);

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Dentist updated successfully!'
        ];

        Core::redirect("admin/dentists/edit?id=" . $id);
    } catch (Exception $e) {
        $errors['global'] = $e->getMessage();
    }
}

Component::header();
Component::sidebar();
?>

<div class="main-wrapper">
    <div class="content">

        <div class="dashboard-header mb-4">
            <div>
                <h3 class="fw-bold mb-1">Edit Dentist</h3>
                <p class="text-muted mb-0">Update dentist and account details</p>
            </div>

            <a href="<?= PROJECT_BASE ?>admin/dentists" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>

        </div>

        <!-- FLASH MESSAGE -->
        <?php if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>">
                <?= $flash['message'] ?>
            </div>
        <?php endif; ?>

        <!-- ERRORS -->
        <?php if (!empty($errors['global'])): ?>
            <div class="alert alert-danger">
                <?= $errors['global'] ?>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-body">

                <form method="POST">

                    <div class="row g-3">

                        <!-- USER -->
                        <div class="col-12">
                            <h5 class="fw-bold">Account Information</h5>
                            <hr>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" readonly
                                value="<?= htmlspecialchars($dentist['name']) ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" readonly
                                value="<?= htmlspecialchars($dentist['email']) ?>">
                        </div>

                        <!-- DENTIST PROFILE -->
                        <div class="col-12 mt-3">
                            <h5 class="fw-bold">Dentist Profile</h5>
                            <hr>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Firstname</label>
                            <input type="text" name="firstname" class="form-control"
                                value="<?= htmlspecialchars($dentist['firstname']) ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Lastname</label>
                            <input type="text" name="lastname" class="form-control"
                                value="<?= htmlspecialchars($dentist['lastname']) ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Specialization</label>
                            <input type="text" name="specialization" class="form-control"
                                value="<?= htmlspecialchars($dentist['specialization']) ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">License Number</label>
                            <input type="text" name="license_number" class="form-control"
                                value="<?= htmlspecialchars($dentist['license_number']) ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Contact</label>
                            <input type="text" name="contact" class="form-control"
                                value="<?= htmlspecialchars($dentist['contact']) ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active" <?= $dentist['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= $dentist['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>

                        <!-- BUTTON -->
                        <div class="col-12 mt-3">
                            <button class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Dentist
                            </button>
                            
                            <a href="<?= PROJECT_BASE ?>admin/dentists/"
                                class="btn btn-light">
                                Cancel
                            </a>
                        </div>

                    </div>

                </form>

            </div>
        </div>

    </div>
    <?php Component::footer(); ?>
</div>