<?php
// Gestion de l'utilisateur connecté (stocké en session)
class Auth
{
    public static function login(array $user): void
    {
        session_regenerate_id(true);   // empêche la fixation de session
        $_SESSION['user'] = [
            'id'   => (int) $user['id'],
            'nom'  => $user['nom'],
            'role' => $user['role'],
        ];
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function hasRole(string $role): bool
    {
        return self::check() && self::user()['role'] === $role;
    }
}
