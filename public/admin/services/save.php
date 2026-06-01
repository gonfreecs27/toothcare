<?php
require '../../../init.php';
Permission::authorize(['admin']);

try {
    Core::loadModel("Service");
    $serviceClass = new Service();
    $id = trim($_POST['id'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = trim($_POST['price'] ?? 0);
    $duration_minutes = trim($_POST['duration_minutes'] ?? 30);

    if (!$name) {
        throw new Exception('Service name is required');
    }

    if (!is_numeric($price) || $price < 0) {
        throw new Exception('Invalid price');
    }

    if (!is_numeric($duration_minutes) || $duration_minutes <= 0) {
        throw new Exception('Invalid duration');
    }

    $data = [
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'duration_minutes' => $duration_minutes
    ];

    if ($id) {
        $service = $serviceClass->find($id);
        if (!$service) {
            throw new Exception('Service not found');
        }

        $serviceClass->update($id, $data);

        Response::success('Service updated successfully');
    }

    $service_id = $serviceClass->create($data);

    Response::success('Service created successfully', ['id' => $service_id]);
} catch (Exception $e) {
    Response::error($e->getMessage(), 422);
}
