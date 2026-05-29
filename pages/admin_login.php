<?php
session_start();

require_once('../classes/database.php');

$con = new Database();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($email) && !empty($password)) {

        $admin = $con->adminLogin($email);

if ($admin) {

    // PLAIN TEXT PASSWORD
    if ($password == $admin['password']) {

        $_SESSION['admin'] = $admin['admin_id'];
        $_SESSION['admin_name'] = $admin['fullname'];

        header("Location: admin_dashboard.php");
        exit();

    } else {

        $error = "Invalid email or password.";

    }

} else {

    $error = "Invalid email or password.";

}
    }
}
?>

<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Dental Clinic System — Login</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body style="background: linear-gradient(135deg, #e0f2ff, #f8fafc);">

<div class="container min-vh-100 d-flex align-items-center justify-content-center">

    <div class="row w-100 justify-content-center">

        <div class="col-12 col-sm-10 col-md-7 col-lg-5 col-xl-4">

            <div class="card border-0 shadow-lg rounded-4">

                <div class="card-body p-5">

                    <!-- HEADER -->
                    <div class="text-center mb-4">

                        <div class="badge bg-primary px-3 py-2 mb-3">
                            Dental Clinic System
                        </div>

                        <h4 class="fw-bold">
                            Welcome Back
                        </h4>

                        <p class="text-muted mb-0">
                            Login to your account
                        </p>

                    </div>

                    <!-- ERROR MESSAGE -->
                    <?php if (!empty($error)): ?>

                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error); ?>
                        </div>

                    <?php endif; ?>

                    <!-- FORM -->
                    <form action="admin_login.php" method="POST">

                        <div class="mb-3">

                            <label class="form-label">
                                Email
                            </label>

                            <input
                                class="form-control form-control-lg"
                                name="email"
                                type="email"
                                placeholder="Enter your email"
                                required
                            >

                        </div>

                        <div class="mb-4">

                            <label class="form-label">
                                Password
                            </label>

                            <input
                                class="form-control form-control-lg"
                                name="password"
                                type="password"
                                placeholder="Enter password"
                                required
                            >

                        </div>

                        <!-- BUTTON -->
                        <button
                            type="submit"
                            class="btn btn-primary w-100 py-2">

                            Login

                        </button>

                    </form>

                </div>

            </div>

            <p class="text-center text-muted mt-3 small">
                © Reyes-Cornejo Dental Clinic System
            </p>

        </div>

    </div>

</div>

</body>
</html>