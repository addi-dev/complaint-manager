<?php
// core/Auth.php

class Auth
{
    // ----------------------------------------------------------------
    // Bootstrap
    // ----------------------------------------------------------------

    public static function init(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // ----------------------------------------------------------------
    // Login / Logout
    // ----------------------------------------------------------------

    /**
     * Attempt login. Returns true on success, false on failure.
     * $user must come from the DB (already fetched by PDO).
     */
    public static function attempt(array $user, string $plainPassword): bool
    {
        if (!$user['actif']) {
            return false;
        }

        if (!password_verify($plainPassword, $user['mot_de_passe'])) {
            return false;
        }

        // Regenerate session ID to prevent session fixation
        session_regenerate_id(true);

        $_SESSION['auth'] = [
            'id'     => $user['id'],
            'nom'    => $user['nom'],
            'prenom' => $user['prenom'],
            'email'  => $user['email'],
            'role'   => $user['role'],   // 'admin' | 'superviseur' | 'agent'
        ];

        return true;
    }

    public static function logout(): void
    {
        self::init();
        $_SESSION = [];
        session_destroy();
    }

    // ----------------------------------------------------------------
    // Checks
    // ----------------------------------------------------------------

    public static function check(): bool
    {
        self::init();
        return isset($_SESSION['auth']);
    }

    /** Redirect (or abort with 401) if not logged in. */
    public static function require(): void
    {
        if (!self::check()) {
            self::abort(401, 'Non authentifié.');
        }
    }

    // ----------------------------------------------------------------
    // Current user
    // ----------------------------------------------------------------

    public static function user(): ?array
    {
        return self::check() ? $_SESSION['auth'] : null;
    }

    public static function id(): ?int
    {
        return self::check() ? (int) $_SESSION['auth']['id'] : null;
    }

    public static function role(): ?string
    {
        return self::check() ? $_SESSION['auth']['role'] : null;
    }

    // ----------------------------------------------------------------
    // Role helpers
    // ----------------------------------------------------------------

    public static function isAdmin(): bool
    {
        return self::role() === 'admin';
    }

    public static function isSuperviseur(): bool
    {
        return self::role() === 'superviseur';
    }

    public static function isAgent(): bool
    {
        return self::role() === 'agent';
    }

    /**
     * Check if the current user has at least one of the given roles.
     *
     * Usage:  Auth::hasRole('admin', 'superviseur')
     */
    public static function hasRole(string ...$roles): bool
    {
        return in_array(self::role(), $roles, true);
    }

    /**
     * Abort with 403 if the user does not have one of the required roles.
     *
     * Usage:  Auth::requireRole('admin', 'superviseur')
     */
    public static function requireRole(string ...$roles): void
    {
        self::require();

        if (!self::hasRole(...$roles)) {
            self::abort(403, 'Accès refusé : rôle insuffisant.');
        }
    }

    // ----------------------------------------------------------------
    // Internal helpers
    // ----------------------------------------------------------------

    private static function abort(int $code, string $message): never
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => $message,
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}
