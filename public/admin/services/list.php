<?php
require '../../../init.php';
Permission::authorize(['all']);

try {
    Core::loadModel("Service");
    $serviceClass = new Service();

    $page = max(1, (int) ($_GET['page'] ?? 1));
    $limit = max(1, (int) ($_GET['limit'] ?? 12));
    $search = trim($_GET['search'] ?? '');
    $duration = $_GET['duration'] ?? null;
    $sort = $_GET['sort'] ?? 'name';

    $services = $serviceClass->paginate(
        $page,
        $limit,
        $search,
        $duration,
        $sort
    );

    Response::success('Services retrieved successfully', $services);
} catch (Exception $e) {
    Response::error('Failed to fetch services', 500);
}
