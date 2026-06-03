<?php
require '../../init.php';

Permission::authorize(['admin', 'staff']);

Core::loadModel('Feedback');

$feedback = new Feedback();
$feedback->approve($_POST['id']);

Response::json([
    'success' => true,
    'message' => 'Feedback approved'
]);
