<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /login.php");
    exit;
}

require_once(__DIR__ . '/../../../models/User.php');

$userClass = new User();

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: /admin/users/");
    exit;
}

if ($id == $_SESSION['user']['id']) {
    header("Location: /admin/users/?error=self_delete");
    exit;
}

try {
    $deleted = $userClass->delete($id);
    header("Location: /admin/users/");
    exit;
} catch (PDOException $e) {
    header("Location: /admin/users/?error=delete_failed");
    exit;
}
