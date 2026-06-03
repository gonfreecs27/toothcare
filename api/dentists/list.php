<?php
require '../../init.php';
Permission::authorize(['all']);

try {
    Core::loadModel("Dentist");
    $dentistClass = new Dentist();
    $dentists = $dentistClass->list();
    Response::success('Dentists retrieved successfully', $dentists);
} catch (Exception $e) {
    Response::error('Failed to fetch dentists', 500);
}
