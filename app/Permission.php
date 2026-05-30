<?php

class Permission {
    public static function getMenus($role) {
        $menus = require_once(__DIR__ . '/../configs/menu.php');
        return $menus[$role] ?? [];
    }

    public static function hasAccess($allowedRoles = []) {
        if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
            return false;
        }

        return in_array($_SESSION['user']['role'], $allowedRoles);
    }

    public static function activeMenu(string $baseUrl, array $pages = []): string {
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

    public static function renderMenu(array $items): void {
        foreach ($items as $item) {
            $title = htmlspecialchars($item['title']);
            $icon  = htmlspecialchars($item['icon']);
            $url   = PROJECT_BASE . htmlspecialchars($item['url']);

            echo '
                <a href="' . $url . '"
                class="sidebar-link ' . self::activeMenu($url, $item['pages']) . '">

                    <i class="' . $icon . '"></i>
                    <span>' . $title . '</span>

                </a>
            ';
        }
    }
}
