<?php

class Core {
    public static function redirect($url) {
        header("Location: " . PROJECT_BASE . $url);
        exit;
    }

    public static function loadModel($model) {
        $path = dirname(__FILE__) . "/../models/$model.php";
        if (file_exists($path)) {
            require_once $path;
            return new $model();
        }
        return null;
    }
}
