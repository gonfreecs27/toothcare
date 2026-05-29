<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /login.php");
    exit;
}

require_once(__DIR__ . '/../../../models/Dentist.php');

$dentistClass = new Dentist();

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: /admin/dentists/");
    exit;
}

try {
    $dentist = $dentistClass->find($id);

    if ($dentist['user_id'] == $_SESSION['user']['id']) {
        header("Location: /admin/dentists/?error=self_delete");
        exit;
    }

    $deleted = $dentistClass->delete($id);

    // Delete user too
    require_once(__DIR__ . '/../../../models/User.php');
    $userClass = new User();
    $userClass->delete($dentist['user_id']);

    header("Location: /admin/dentists/");
    exit;
} catch (PDOException $e) {
    header("Location: /admin/dentists/?error=delete_failed");
    exit;
}
