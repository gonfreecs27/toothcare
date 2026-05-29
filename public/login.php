<?php
session_start();

require_once __DIR__ . '/../models/User.php';

$userModel = new User();
$error = "";

if (isset($_SESSION['user'])) {
    header("Location: /loading");
    exit;
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

            header("Location: /loading");
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
    <title>Login | ToothCare Dental Clinic System</title>
    <link rel="icon" href="/assets/images/icon.png" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/login.css">
</head>

<body>
    <div class="login-box">

        <div class="brand">Tooth<span>Care</span></div>
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
            <a href="/register">Create Account</a>
            <a href="/"><i class="bi bi-arrow-left"></i> Back to Home</a>
        </div>

        <div class="footer">
            © <?= date('Y') ?> ToothCare Dental Clinic System
        </div>

    </div>

</body>

</html>