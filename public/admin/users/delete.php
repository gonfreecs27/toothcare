<?php
require '../../../init.php';
Permission::authorize(['admin']);

Core::loadModel("User");
$userClass = new User();
$id = $_GET['id'] ?? null;

if (!$id) {
    Core::redirect("admin/users/");
}

if ($id == $_SESSION['user']['id']) {
    Core::redirect("admin/users/?error=self_delete");
}

try {
    $deleted = $userClass->delete($id);
    Core::redirect("admin/users/");
} catch (PDOException $e) {
    Core::redirect("admin/users/?error=delete_failed");
}
