<?php
require '../init.php';

if (!isset($_SESSION['user'])) {
    Core::redirect("login");
}

$role = $_SESSION['user']['role'];

$routes = [
    'admin' => PROJECT_BASE . 'admin/dashboard',
    'dentist' => PROJECT_BASE . 'dentist/dashboard',
    'staff' => PROJECT_BASE . 'staff/dashboard'
];

$redirect = $routes[$role] ?? PROJECT_BASE . 'staff/dashboard';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Loading <?= BRAND_NAME ?>...</title>
    <link rel="icon" href="<?= PROJECT_BASE ?>assets/images/icon.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #e6f2ff, #f7fbff);
            font-family: Arial;
        }

        .loader-box {
            text-align: center;
        }

        .brand {
            font-size: 26px;
            font-weight: bold;
            color: #0d6efd;
            margin-bottom: 10px;
        }

        .brand span {
            color: #20c997;
        }

        .spinner {
            width: 55px;
            height: 55px;
            border: 5px solid #eee;
            border-top: 5px solid #0d6efd;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .text {
            font-size: 14px;
            color: #666;
        }
    </style>
</head>

<body>

    <div class="loader-box">

        <div class="brand"><?= BRAND_NAME_FIRST ?><span><?= BRAND_NAME_SECOND ?></span></div>

        <div class="spinner"></div>

        <div class="text">Logging you in, please wait...</div>

    </div>

    <script>
        setTimeout(() => {
            window.location.href = "<?= $redirect ?>";
        }, 2500);
    </script>

</body>

</html>