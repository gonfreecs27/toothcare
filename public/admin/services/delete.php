<?php
require '../../../init.php';
header('Content-Type: application/json');

if (!Permission::hasAccess(['admin'])) {
    http_response_code(401);
    echo json_encode([
        'error' => 'Unauthorized'
    ]);
    exit;
}

try {
    Core::loadModel("Service");
    $serviceClass = new Service();
    $id = trim($_GET['id'] ?? '');

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

    echo json_encode([
        'success' => true,
        'message' => 'Service deleted successfully'
    ]);
} catch (Exception $e) {

    http_response_code(422);

    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
