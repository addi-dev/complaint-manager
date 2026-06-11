<?php
// core/CSRF.php
class CSRF
{
    private const SESSION_KEY = 'csrf_token';
    private const HEADER_NAME = 'X-CSRF-Token';
    private const TOKEN_BYTES = 32;
    public static function getToken(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (empty($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = self::generateToken();
        }
        return $_SESSION[self::SESSION_KEY];
    }
    public static function regenerate(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION[self::SESSION_KEY] = self::generateToken();
        return $_SESSION[self::SESSION_KEY];
    }
    public static function verify(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $sessionToken = $_SESSION[self::SESSION_KEY] ?? '';
        $headerToken = self::getRequestHeader(self::HEADER_NAME);
        if ($headerToken === null) {
            $headerToken = $_POST['_csrf'] ?? '';
        }
        if (
            empty($sessionToken) ||
            empty($headerToken) ||
            !hash_equals($sessionToken, $headerToken)
        ) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'CSRF TOKEN NON VALID',
            ]);
            exit;
        }
    }
    public static function field(): string
    {
        return sprintf(
            '<input type="hidden" name="_csrf" value="%s">',
            htmlspecialchars(self::getToken(), ENT_QUOTES, 'UTF-8')
        );
    }
    public static function metaTag(): string
    {
        return sprintf(
            '<meta name="csrf-token" content="%s">',
            htmlspecialchars(self::getToken(), ENT_QUOTES, 'UTF-8')
        );
    }
    private static function generateToken(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(self::TOKEN_BYTES)), '+/', '-_'), '=');
    }
    private static function getRequestHeader(string $name): ?string
    {
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
            foreach ($headers as $key => $value) {
                if (strcasecmp($key, $name) === 0) {
                    return $value;
                }
            }
        }
        $serverKey = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        return isset($_SERVER[$serverKey]) ? $_SERVER[$serverKey] : null;
    }
}
