<?php
// Core files
require dirname(__FILE__) . '/' . 'app/Core.php';
require dirname(__FILE__) . '/' . 'app/Component.php';
require dirname(__FILE__) . '/' . 'app/Permission.php';
require dirname(__FILE__) . '/' . 'app/Response.php';

// Config files
require dirname(__FILE__) . '/' . 'configs/project.php';

// Start session for user authentication
session_start();
