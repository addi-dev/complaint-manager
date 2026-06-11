<?php
// core/Response.php
class Response
{
    public static function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    public static function redirect(string $url): void
    {
        header("Location: " . $url);
        exit;
    }
    public static function forbidden(string $message = 'Forbidden'): void
    {
        self::json(['success' => false, 'message' => $message], 403);
    }
    public static function notFound(string $message = 'Not found'): void
    {
        self::json(['success' => false, 'message' => $message], 404);
    }
    public static function methodNotAllowed(): void
    {
        self::json(['success' => false, 'message' => 'Method not allowed.'], 405);
    }
    public static function unauthorized(string $message = 'Unauthorized'): void
    {
        self::json(['success' => false, 'message' => $message], 401);
    }
    public static function success(string $message, array $extra = []): void
    {
        self::json(array_merge(['success' => true, 'message' => $message], $extra));
    }
    public static function error(string $message, int $status = 400): void
    {
        self::json(['success' => false, 'message' => $message], $status);
    }
}
