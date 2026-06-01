<?php
require_once __DIR__ . '/Response.php';
class Auth
{

    public static function check(): bool
    {
        return !empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    public static function user(): ?array
    {
        if (!self::check()) return null;
        return [
            'id'    => $_SESSION['user_id'],
            'name'  => $_SESSION['user_name'],
            'email' => $_SESSION['user_email'],
            'role'  => $_SESSION['user_role'],
            'table' => $_SESSION['user_table'],
        ];
    }

    public static function requireRole(string ...$roles): void
    {
        if (!self::check() || !in_array($_SESSION['user_role'], $roles)) {
            Response::redirect('/complaint-manager/views/auth/login.php');
        }
    }
}
