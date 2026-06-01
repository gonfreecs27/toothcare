<?php

class Response {
    /**
     * Return JSON response
     */
    public static function json($data = [], $httpCode = 200) {
        http_response_code($httpCode);

        header('Content-Type: application/json');

        echo json_encode($data);

        exit;
    }

    /**
     * Success shortcut
     */
    public static function success($message = 'Success', $data = []) {
        self::json([
            'success' => true,
            'message' => $message,
            'data'    => $data
        ]);
    }

    /**
     * Error shortcut
     */
    public static function error($message = 'Error', $httpCode = 400) {
        self::json([
            'success' => false,
            'message' => $message,
            'error'   => $message
        ], $httpCode);
    }
}
