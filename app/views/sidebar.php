<?php

$user = $_SESSION['user'] ?? [];
$role = $user['role'] ?? '';
$menus = Permission::getMenus($role);
?>

<div class="sidebar">

    <div class="brand-section">
        <div class="brand-logo">
            <i class="bi bi-heart-pulse-fill"></i>
        </div>
        <div>

            <h3 class="fw-bold text-primary mb-0">
                <?= BRAND_NAME_FIRST ?><span class="text-info"><?= BRAND_NAME_SECOND ?></span>
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
        Permission::renderMenu($menus);
        ?>

    </div>

    <div class="sidebar-footer">

        <a href="<?= PROJECT_BASE ?>logout" class="logout-btn">

            <i class="bi bi-box-arrow-left"></i>
            <span>Logout</span>

        </a>

    </div>

</div>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<div class="mobile-header">
    <div class="mobile-brand">
        <?= BRAND_NAME_FIRST ?><span class="text-info"><?= BRAND_NAME_SECOND ?></span>
    </div>

    <button class="menu-toggle" id="menuToggle">
        <i class="bi bi-list"></i>
    </button>
</div>