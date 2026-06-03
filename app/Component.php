<?php

class Component {
    public static function header($public = false, $title = null, $additional_js = [], $additional_css = []) {
        if ($public) {
            include __DIR__ . "/views/header_public.php";
        } else {
            include __DIR__ . "/views/header_app.php";
        }
    }

    public static function sidebar() {
        include __DIR__ . "/views/sidebar.php";
    }

    public static function footer($public = false) {
        if ($public) {
            include __DIR__ . "/views/footer_public.php";
        } else {
            include __DIR__ . "/views/footer_app.php";
        }
    }

    public static function featureCard($icon, $title, $desc) {
        echo "
        <div class='feature-card'>
            <div class='feature-icon'>
                <i class='bi $icon'></i>
            </div>
            <h5 class='fw-semibold'>$title</h5>
            <p class='text-muted'>$desc</p>
        </div>";
    }

    public static function statBox($value, $label) {
        echo "
        <div class='stat-box'>
            <h3 class='text-primary fw-bold'>$value</h3>
            <p class='mb-0'>$label</p>
        </div>";
    }

    public static function alert($type, $message) {
        return "<div class='alert alert-$type py-2 small'>$message</div>";
    }

    public static function inputIcon($icon, $type, $name, $placeholder, $value = '') {
        return "
        <div class='input-group mb-2'>
            <span class='input-group-text'>
                <i class='bi bi-$icon'></i>
            </span>
            <input
                type='$type'
                name='$name'
                class='form-control'
                placeholder='$placeholder'
                value='" . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . "'
                required
            >
        </div>
        ";
    }

    public static function getEmailContent($component, $params = []) {
        extract($params);
        $content = include __DIR__ . "/emails/" . $component . ".php";
        return $content;
    }
}
