<?php
require '../../init.php';

Permission::authorize(['admin']);

Core::loadModel('Feedback');

$feedback = new Feedback();
$feedback->toggleFeatured($_POST['id']);

Response::json([
    'success' => true,
    'message' => 'Featured status updated'
]);
