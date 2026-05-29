<?php
session_start();

require_once '../models/User.php';
require_once '../includes/components.php';

$userModel = new User();

$error = "";
$success = "";

if (isset($_SESSION['user'])) {
    header("Location: /loading");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm = trim($_POST['confirm_password'] ?? '');

    if (!$name || !$email || !$password || !$confirm) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif ($userModel->login($email)) {
        $error = "Email already exists.";
    } else {

        $userModel->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => 'staff'
        ]);

        $success = "Account created successfully.";
    }
}

include '../includes/header_public.php';

?>
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">

    <div class="card shadow-lg p-4" style="width: 420px; border-radius: 14px;">

        <div class="text-center mb-3">
            <h3 class="fw-bold text-primary">Tooth<span class="text-info">Care</span></h3>
            <small class="text-muted">Create Staff Account</small>
        </div>

        <?php if ($error): ?>
            <?= alert('danger', $error) ?>
        <?php endif; ?>

        <?php if ($success): ?>
            <?= alert('success', $success) ?>
        <?php endif; ?>

        <form method="POST">

            <?= inputIcon('person', 'text', 'name', 'Full Name') ?>
            <?= inputIcon('envelope', 'email', 'email', 'Email Address') ?>
            <?= inputIcon('lock', 'password', 'password', 'Password') ?>
            <?= inputIcon('shield-lock', 'password', 'confirm_password', 'Confirm Password') ?>

            <button class="btn btn-primary w-100 mt-2">
                <i class="bi bi-person-plus"></i> Create Account
            </button>

        </form>

        <div class="text-center mt-3 small">
            Already have an account?
            <a href="/login">Login</a>
        </div>
    </div>

</div>

<?php include '../includes/footer_public.php'; ?>