<?php
require '../../init.php';
Permission::authorize(['admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Response::error('Invalid request method', 405);
}

try {
    Core::loadModel("Service");
    $serviceClass = new Service();
    $id = trim($_POST['id'] ?? '');

    if (!$id) {
        throw new Exception('Service ID is required');
    }

    $service = $serviceClass->find($id);
    if (!$service) {
        throw new Exception('Service not found');
    }

    $deleted = $serviceClass->delete($id);
    if (!$deleted) {
        throw new Exception('Failed to delete service');
    }

    Response::success('Service deleted successfully');
} catch (Exception $e) {
    Response::error($e->getMessage(), 422);
}
