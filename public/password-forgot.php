<?php
require '../init.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');

    if (!$email) {
        $error = 'Please enter your email address.';
    } else {
        Core::loadModel("User");
        Core::loadModel("PasswordReset");

        $userModel = new User();
        $passwordResetModel = new PasswordReset();

        // 1. Verify if email exists
        $user = $userModel->findByEmail($email);
        if ($user) {
            $token = $passwordResetModel->createToken(
                $user['id']
            );

            $resetLink = COMPLETE_DOMAIN . 'password-reset?token=' . urlencode($token);
            $htmlBody = Component::getEmailContent("forgot-password", [
                "username" => $user['name'],
                "resetLink" => $resetLink
            ]);

            Mailer::send(
                $user['email'],
                'Password Reset Request',
                $htmlBody
            );

            $message = "Reset link has been sent to your email.";
        } else {
            $error = "No account found with that email address.";
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Forgot Password | <?= BRAND_NAME ?>: Dental Clinic System</title>

    <link rel="icon"
        href="<?= PROJECT_BASE ?>assets/images/icon.png"
        type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet">

    <link rel="stylesheet"
        href="<?= PROJECT_BASE ?>assets/css/login.css">
</head>

<body>

    <div class="login-box">

        <div class="brand">
            <?= BRAND_NAME_FIRST ?>
            <span><?= BRAND_NAME_SECOND ?></span>
        </div>

        <div class="subtitle">
            Account Recovery
        </div>

        <div class="text-center mb-4">
            <i class="bi bi-shield-lock fs-1 text-primary"></i>

            <p class="text-muted mt-3 mb-0">
                Enter the email address associated with your account.
                We'll send you instructions to reset your password.
            </p>
        </div>


        <?php if ($message): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php else: ?>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

        <?php endif; ?>

        <form method="POST">

            <div class="input-icon">
                <i class="bi bi-envelope"></i>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="Email Address"
                    required>
            </div>

            <button
                type="submit"
                class="btn btn-login w-100 mt-2">

                <i class="bi bi-send"></i>
                Send Reset Link

            </button>

        </form>

        <div class="links">
            <a href="<?= PROJECT_BASE ?>login">
                <i class="bi bi-arrow-left"></i>
                Back to Login
            </a>
        </div>

        <div class="footer">
            © <?= date('Y') ?>
            <?= BRAND_NAME ?>
            : Dental Clinic System
        </div>

    </div>

</body>

</html>