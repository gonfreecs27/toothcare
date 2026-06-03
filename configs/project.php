<?php

/**
 * ------------------------------------------------------------
 * Application Base Path
 * ------------------------------------------------------------
 * Defines the root URL where the application is hosted.
 *
 * Example:
 * - Local development: /toothcare/public/
 * - Production domain: /
 *
 * If deploying to a dedicated domain, update this value
 * accordingly and enable the production .htaccess file.
 *
 * Example:
 * define('PROJECT_BASE', '/');
 */
define('PROJECT_DOMAIN', 'http://localhost');
define('PROJECT_BASE', '/toothcare/public/');
define('COMPLETE_DOMAIN', PROJECT_DOMAIN . PROJECT_BASE);

/**
 * ------------------------------------------------------------
 * Application Information
 * ------------------------------------------------------------
 * General application metadata used throughout the system
 * such as page titles, footer information, and versioning.
 */
define('BRAND_NAME', 'ToothCare');
define('PROJECT_VERSION', '1.0.0');


/**
 * ------------------------------------------------------------
 * Brand Name Segments
 * ------------------------------------------------------------
 * Useful when displaying the application logo with
 * different styles, colors, or font weights.
 *
 * Example:
 * <span>Tooth</span><span>Care</span>
 */
define('BRAND_NAME_FIRST', 'Reyes');
define('BRAND_NAME_SECOND', 'Cornejo');