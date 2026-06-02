<?php
// core/CSRF.php
// ============================================================
// CSRF Protection — Double-Submit Cookie / Session Token Pattern
// ============================================================
//
// HOW IT WORKS
// ------------
// 1. On first page load (or when the token is missing/expired), a
//    cryptographically random token is generated and stored in the
//    PHP session ($_SESSION['csrf_token']).
// 2. An endpoint (api/csrf_token_api.php) returns this token as JSON
//    so JavaScript can fetch it once and attach it to every mutating
//    request via the custom header  X-CSRF-Token.
// 3. Every action file that handles POST/PUT/DELETE calls
//    CSRF::verify() at the top. The method compares the value from
//    the X-CSRF-Token header against the session value using a
//    timing-safe comparison. Mismatch → 403 + exit.
//
// WHY A CUSTOM HEADER?
// --------------------
// Browsers enforce the Same-Origin Policy for custom headers:
// a cross-origin page cannot set X-CSRF-Token on a fetch() without
// CORS pre-flight approval. Since the API does NOT send a wildcard
// Access-Control-Allow-Headers, an attacker's page cannot forge the
// header — even if it somehow knows the token value.
//
// INTEGRATION SUMMARY
// -------------------
//   PHP (action files)   →  CSRF::verify()  at the top of every POST handler
//   PHP (view files)     →  CSRF::getToken() to embed in a <meta> tag (optional)
//   JavaScript (app.js)  →  fetch /api/csrf_token_api.php once, store in module
//                           variable, then include header on every mutating fetch
// ============================================================

class CSRF
{
    /** Session key where the token is stored. */
    private const SESSION_KEY = 'csrf_token';

    /** HTTP header the client must send on every mutating request. */
    private const HEADER_NAME = 'X-CSRF-Token';

    /** Token byte-length before base64 encoding (32 bytes = 256-bit entropy). */
    private const TOKEN_BYTES = 32;

    // ------------------------------------------------------------------
    // Token generation & retrieval
    // ------------------------------------------------------------------

    /**
     * Return the current session CSRF token, generating one if absent.
     *
     * Call this from a view or a dedicated API endpoint so that the
     * JavaScript layer can retrieve it.
     *
     * @return string  Base64url-encoded 256-bit random token.
     */
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

    /**
     * Regenerate the session token.
     *
     * Call this after a successful login (alongside session_regenerate_id)
     * so that a pre-authentication token cannot be reused post-login.
     *
     * @return string  The new token.
     */
    public static function regenerate(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION[self::SESSION_KEY] = self::generateToken();
        return $_SESSION[self::SESSION_KEY];
    }

    // ------------------------------------------------------------------
    // Verification
    // ------------------------------------------------------------------

    /**
     * Verify the CSRF token submitted by the client.
     *
     * Reads the token from the X-CSRF-Token request header (preferred
     * for JSON/fetch-based APIs) or falls back to a POST body field
     * named "_csrf" for traditional HTML form submissions.
     *
     * On failure: sends a 403 JSON response and exits.
     * On success: returns silently.
     *
     * Usage at the top of every action file:
     *
     *   require_once __DIR__ . '/../../core/CSRF.php';
     *   CSRF::verify();
     */
    public static function verify(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $sessionToken = $_SESSION[self::SESSION_KEY] ?? '';

        // 1. Try the custom request header (used by fetch / AJAX calls).
        $headerToken = self::getRequestHeader(self::HEADER_NAME);

        // 2. Fall back to a POST body field for plain HTML forms.
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
                'message' => 'Invalid or missing CSRF token.',
            ]);
            exit;
        }
    }

    // ------------------------------------------------------------------
    // HTML helpers
    // ------------------------------------------------------------------

    /**
     * Render a hidden <input> field for traditional HTML form submissions.
     *
     * Example usage inside a <form>:
     *   <?php echo CSRF::field(); ?>
     *
     * @return string  A self-closing <input type="hidden"> tag.
     */
    public static function field(): string
    {
        return sprintf(
            '<input type="hidden" name="_csrf" value="%s">',
            htmlspecialchars(self::getToken(), ENT_QUOTES, 'UTF-8')
        );
    }

    /**
     * Render a <meta> tag so the JS layer can read the token without a
     * separate HTTP round-trip on pages that already embed it server-side.
     *
     * Place inside <head>:
     *   <?php echo CSRF::metaTag(); ?>
     *
     * Read in JavaScript:
     *   const token = document.querySelector('meta[name="csrf-token"]').content;
     *
     * @return string  A <meta> tag string.
     */
    public static function metaTag(): string
    {
        return sprintf(
            '<meta name="csrf-token" content="%s">',
            htmlspecialchars(self::getToken(), ENT_QUOTES, 'UTF-8')
        );
    }

    // ------------------------------------------------------------------
    // Private helpers
    // ------------------------------------------------------------------

    /**
     * Generate a cryptographically secure random token.
     *
     * @return string  Base64url-encoded token (URL-safe, no padding).
     */
    private static function generateToken(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(self::TOKEN_BYTES)), '+/', '-_'), '=');
    }

    /**
     * Read a request header value regardless of the web server's naming
     * convention (Apache uses HTTP_* keys; some stacks pass the raw name).
     *
     * @param  string      $name  Header name, e.g. "X-CSRF-Token".
     * @return string|null        Header value, or null if not present.
     */
    private static function getRequestHeader(string $name): ?string
    {
        // getallheaders() is available on Apache and most SAPI environments.
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
            // Header names are case-insensitive per RFC 7230.
            foreach ($headers as $key => $value) {
                if (strcasecmp($key, $name) === 0) {
                    return $value;
                }
            }
        }

        // Fallback: look up in $_SERVER using the HTTP_* convention.
        $serverKey = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        return isset($_SERVER[$serverKey]) ? $_SERVER[$serverKey] : null;
    }
}
