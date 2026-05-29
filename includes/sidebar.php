<?php

$user = $_SESSION['user'] ?? [];
$role = $user['role'] ?? '';

$menus = require_once(__DIR__ . '/../configs/menu.php');

function activeMenu(string $baseUrl, array $pages = []): string {
    $currentPath = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $baseUrl = rtrim($baseUrl, '/');

    // if current URL is exactly the base (e.g. /admin/patients)
    if ($currentPath === $baseUrl) {
        return 'active-menu';
    }

    foreach ($pages as $page) {
        $fullPath = $baseUrl . '/' . $page;
        $fullPath = rtrim($fullPath, '.php');

        if ($currentPath === $fullPath) {
            return 'active-menu';
        }
    }

    return '';
}

function renderMenu(array $items): void {
    foreach ($items as $item) {

        $title = htmlspecialchars($item['title']);
        $icon  = htmlspecialchars($item['icon']);
        $url   = htmlspecialchars($item['url']);

        echo '
            <a href="' . $url . '"
               class="sidebar-link ' . activeMenu($item['url'], $item['pages']) . '">

                <i class="' . $icon . '"></i>
                <span>' . $title . '</span>

            </a>
        ';
    }
}

?>

<div class="sidebar">

    <div class="brand-section">

        <div class="brand-logo">
            <i class="bi bi-heart-pulse-fill"></i>
        </div>

        <div>

            <h3 class="fw-bold text-primary mb-0">
                Tooth<span class="text-info">Care</span>
            </h3>

            <small class="text-muted">
                Dental Clinic System
            </small>

        </div>

    </div>

    <div class="user-card">

        <div class="user-avatar">
            <i class="bi bi-person-fill"></i>
        </div>

        <div class="user-info">

            <div class="user-name">
                <?= htmlspecialchars($user['name'] ?? '') ?>
            </div>

            <div class="user-role text-capitalize">
                <?= htmlspecialchars($role) ?>
            </div>

        </div>

    </div>

    <div class="menu-label">
        MAIN MENU
    </div>

    <div class="sidebar-menu">

        <?php
        renderMenu($menus['common'] ?? []);
        renderMenu($menus[$role] ?? []);
        ?>

    </div>

    <div class="sidebar-footer">

        <a href="/logout" class="logout-btn">

            <i class="bi bi-box-arrow-left"></i>
            <span>Logout</span>

        </a>

    </div>

</div>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<div class="mobile-header">
    <div class="mobile-brand">
        Tooth<span class="text-info">Care</span>
    </div>

    <button class="menu-toggle" id="menuToggle">
        <i class="bi bi-list"></i>
    </button>
</div>