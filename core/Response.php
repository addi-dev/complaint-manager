<?php
// core/Response.php

class Response
{

    /**
     * Send a JSON response and stop execution
     */
    public static function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Redirect to a URL and stop execution
     */
    public static function redirect(string $url): void
    {
        header("Location: " . $url);
        exit;
    }

    /**
     * Send a 403 Forbidden and stop execution
     */
    public static function forbidden(string $message = 'Forbidden'): void
    {
        self::json(['success' => false, 'message' => $message], 403);
    }

    /**
     * Send a 404 Not Found and stop execution
     */
    public static function notFound(string $message = 'Not found'): void
    {
        self::json(['success' => false, 'message' => $message], 404);
    }

    /**
     * Send a 405 Method Not Allowed and stop execution
     */
    public static function methodNotAllowed(): void
    {
        self::json(['success' => false, 'message' => 'Method not allowed.'], 405);
    }

    /**
     * Send a 401 Unauthorized and stop execution
     */
    public static function unauthorized(string $message = 'Unauthorized'): void
    {
        self::json(['success' => false, 'message' => $message], 401);
    }

    /**
     * Shortcut for a success JSON response
     */
    public static function success(string $message, array $extra = []): void
    {
        self::json(array_merge(['success' => true, 'message' => $message], $extra));
    }

    /**
     * Shortcut for an error JSON response
     */
    public static function error(string $message, int $status = 400): void
    {
        self::json(['success' => false, 'message' => $message], $status);
    }
}
