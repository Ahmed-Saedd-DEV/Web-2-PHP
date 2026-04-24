<?php
class Auth {
    public static function requireLogin(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user_id'])) {
            header('Location: /index.php');
            exit;
        }
    }

    public static function requireRole(string ...$roles): void {
        self::requireLogin();
        if (!in_array($_SESSION['role'], $roles, true)) {
            header('Location: /index.php?error=unauthorized');
            exit;
        }
    }

    public static function setSession(array $user): void {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
    }

    public static function logout(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        header('Location: /index.php');
        exit;
    }

    public static function isLoggedIn(): bool {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return !empty($_SESSION['user_id']);
    }
}
