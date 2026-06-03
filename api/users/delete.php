<?php
require '../../init.php';
Permission::authorize(['admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Response::error('Invalid request method', 405);
}

$id = $_POST['id'] ?? null;

if (!$id) {
    Response::error('User ID is required', 422);
}

if ($id == $_SESSION['user']['id']) {
    Response::error('You cannot delete your own account', 422);
}

try {
    Core::loadModel("User");
    $userClass = new User();

    $deleted = $userClass->delete($id);
    Response::success('User deleted successfully');
} catch (Exception $e) {
    Response::error('Failed to delete user', 500);
}
