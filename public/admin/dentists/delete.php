<?php
require '../../../init.php';

if (!Permission::hasAccess(['admin'])) {
    Core::redirect("login");
}

Core::loadModel("Dentist");
$dentistClass = new Dentist();

$id = $_GET['id'] ?? null;

if (!$id) {
    Core::redirect("admin/dentists/");
}

try {
    $dentist = $dentistClass->find($id);

    if ($dentist['user_id'] == $_SESSION['user']['id']) {
        Core::redirect("admin/dentists/?error=self_delete");
    }

    $deleted = $dentistClass->delete($id);

    // Delete user too
    Core::loadModel("User");
    $userClass = new User();
    $userClass->delete($dentist['user_id']);

    Core::redirect("admin/dentists/");
} catch (PDOException $e) {
    Core::redirect("admin/dentists/?error=delete_failed");
}
