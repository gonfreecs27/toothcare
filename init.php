<?php
/**
 * ------------------------------------------------------
 * Set the default timezone for all PHP date/time functions
 * This ensures consistency across the entire application
 * (e.g., date(), strtotime(), DateTime, logs, etc.)
 * Using Asia/Manila for Philippine-based system (ToothCare)
 */
date_default_timezone_set('Asia/Manila');

/**
 * ------------------------------------------------------------
 * Composer Autoloader
 * ------------------------------------------------------------
 * Loads all dependencies installed via Composer.
 * This includes third-party libraries such as:
 * - PHPMailer
 * - Any other external packages
 *
 * Ensures classes are automatically loaded without manual includes.
 */
require_once __DIR__ . '/vendor/autoload.php';


/**
 * ------------------------------------------------------------
 * Core Framework Files
 * ------------------------------------------------------------
 * These are the foundational classes of the application:
 *
 * - Core.php       → Bootstraps the system (loader, routing helpers, etc.)
 * - Component.php  → Reusable UI or system components
 * - Permission.php → Handles role-based access control (RBAC)
 * - Response.php   → Standardized API/HTTP response formatting
 */
require dirname(__FILE__) . '/' . 'app/Core.php';
require dirname(__FILE__) . '/' . 'app/Component.php';
require dirname(__FILE__) . '/' . 'app/Permission.php';
require dirname(__FILE__) . '/' . 'app/Response.php';


/**
 * ------------------------------------------------------------
 * Helper Utilities
 * ------------------------------------------------------------
 * Utility classes that provide shared functionality across the system.
 *
 * Example:
 * - Mailer.php → Centralized email sending (SMTP wrapper)
 */
require dirname(__FILE__) . '/' . 'app/helpers/Mailer.php';


/**
 * ------------------------------------------------------------
 * Application Configuration Files
 * ------------------------------------------------------------
 * Centralized configuration values for the system.
 *
 * - project.php → App name, base URL, version, branding
 * - mail.php    → SMTP / email service configuration
 */
require dirname(__FILE__) . '/' . 'configs/project.php';
require dirname(__FILE__) . '/' . 'configs/mail.php';


/**
 * ------------------------------------------------------------
 * Session Initialization
 * ------------------------------------------------------------
 * Starts PHP session handling for the application.
 *
 * This enables:
 * - User authentication (login/logout)
 * - Storing user roles and permissions
 * - Flash messages and temporary data
 *
 * Must be called before any output is sent to the browser.
 */
session_start();
