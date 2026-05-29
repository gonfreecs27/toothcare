<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    http_response_code(401);

    echo json_encode([
        'error' => 'Unauthorized'
    ]);

    exit;
}

require_once(__DIR__ . '/../../../models/Service.php');

try {

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

    echo json_encode($services);
} catch (Exception $e) {

    http_response_code(500);

    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
