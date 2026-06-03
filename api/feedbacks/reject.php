<?php
require '../../init.php';

Permission::authorize(['admin', 'staff']);
Core::loadModel('Feedback');
$feedback = new Feedback();
$feedback->reject($_POST['id']);
Response::json([
    'success' => true,
    'message' => 'Feedback rejected'
]);
