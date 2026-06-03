<?php
require '../init.php';

Core::loadModel("User");
Core::loadModel("PasswordReset");

$userModel = new User();
$passwordResetModel = new PasswordReset();

$token = $_GET['token'] ?? '';
$error = '';
$success = '';

// ---------------------------------
// 1. Validate token
// ---------------------------------
$reset = null;

if ($token) {
    $reset = $passwordResetModel->findValidToken($token);
}

if (!$reset) {
    $error = "This password reset link is invalid or has expired.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $reset) {

    $password = trim($_POST['password'] ?? '');
    $confirm  = trim($_POST['confirm_password'] ?? '');

    if (!$password || !$confirm) {
        $error = "Please fill in all fields.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $passwordValidation = $userModel->validatePassword($password);
        if (!$passwordValidation['valid']) {
            $error = $passwordValidation['message'];
        } else {
            // 1. Update user password
            $userModel->updatePassword(
                $reset['user_id'],
                $password
            );
    
            // 2. Delete reset token
            $passwordResetModel->deleteByUser(
                $reset['user_id']
            );
            $success = "Password reset successfully. You may now login.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Reset Password | <?= BRAND_NAME ?></title>

    <link rel="icon" href="<?= PROJECT_BASE ?>assets/images/icon.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="<?= PROJECT_BASE ?>assets/css/login.css">
</head>

<body>

    <div class="login-box">

        <div class="brand">
            <?= BRAND_NAME_FIRST ?><span><?= BRAND_NAME_SECOND ?></span>
        </div>

        <div class="subtitle">
            Reset Password
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>

            <div class="alert alert-success">
                <?= htmlspecialchars($success) ?>
            </div>

            <a href="<?= PROJECT_BASE ?>login" class="btn btn-login w-100">
                <i class="bi bi-box-arrow-in-right"></i> Go to Login
            </a>

        <?php elseif ($reset): ?>

            <form method="POST">

                <div class="input-icon mb-3">
                    <i class="bi bi-lock"></i>
                    <input type="password"
                        name="password"
                        class="form-control"
                        placeholder="New Password"
                        required>
                </div>

                <div class="input-icon mb-3">
                    <i class="bi bi-lock-fill"></i>
                    <input type="password"
                        name="confirm_password"
                        class="form-control"
                        placeholder="Confirm Password"
                        required>
                </div>

                <button type="submit" class="btn btn-login w-100">
                    <i class="bi bi-check-circle"></i> Reset Password
                </button>

            </form>

        <?php else: ?>

            <a href="<?= PROJECT_BASE ?>password-forgot" class="btn btn-secondary w-100">
                Back to Forgot Password
            </a>

        <?php endif; ?>

        <div class="links mt-3">
            <a href="<?= PROJECT_BASE ?>login">
                <i class="bi bi-arrow-left"></i> Back to Login
            </a>
        </div>

    </div>

</body>

</html>