<?php
require '../init.php';

Core::loadModel("User");
$userModel = new User();
$error = "";

if (isset($_SESSION['user'])) {
    Core::redirect("loading");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$email || !$password) {
        $error = "Please enter email and password.";
    } else {

        $user = $userModel->verifyPassword($email, $password);

        if ($user) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role']
            ];

            Core::redirect("loading");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login | <?= BRAND_NAME ?>: Dental Clinic System</title>
    <link rel="icon" href="<?= PROJECT_BASE ?>assets/images/icon.png" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= PROJECT_BASE ?>assets/css/login.css">
</head>

<body>
    <div class="login-box">

        <div class="brand"><?= BRAND_NAME_FIRST ?><span><?= BRAND_NAME_SECOND ?></span></div>
        <div class="subtitle">Dental Clinic Management System</div>

        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">

            <div class="input-icon">
                <i class="bi bi-envelope"></i>
                <input type="email" name="email" class="form-control" placeholder="Email Address" required>
            </div>

            <div class="input-icon">
                <i class="bi bi-lock"></i>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <button type="submit" class="btn btn-login w-100 mt-2">
                <i class="bi bi-box-arrow-in-right"></i> Sign In
            </button>

        </form>

        <div class="links">
            <a href="<?= PROJECT_BASE ?>register">Create Account</a>
            <a href="<?= PROJECT_BASE ?>forget">Forget Password</a>
            <a href="<?= PROJECT_BASE ?>"><i class="bi bi-arrow-left"></i> Back to Home</a>
        </div>

        <div class="footer">
            © <?= date('Y') ?> <?= BRAND_NAME ?>: Dental Clinic System
        </div>

    </div>

</body>

</html>