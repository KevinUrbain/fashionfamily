<?php
class Auth
{
    /**
     * Indique si un utilisateur est actuellement connecté.
     *
     * @return bool true si la session contient un identifiant utilisateur.
     */
    public static function isLoggedIn(): bool
    {
        return Session::has('user_id');
    }

    /**
     * Indique si l'utilisateur connecté possède le rôle admin.
     *
     * @return bool true si le rôle en session est "admin".
     */
    public static function isAdmin(): bool
    {
        return Session::get('role') === 'admin';
    }

    /**
     * Retourne l'identifiant de l'utilisateur connecté.
     *
     * @return int|null null si aucun utilisateur n'est connecté.
     */
    public static function currentUserId(): ?int
    {
        $id = Session::get('user_id');
        return $id ? (int) $id : null;
    }

    /**
     * Ouvre une session utilisateur en stockant l'identifiant et le rôle.
     *
     * @param array{id: int, role: string} $user Données de l'utilisateur authentifié.
     */
    public static function login(array $user): void
    {
        Session::set('user_id', $user['id']);
        Session::set('role', $user['role']);
    }

    /**
     * Ferme la session de l'utilisateur en la détruisant complètement.
     */
    public static function logout(): void
    {
        Session::destroy();
    }

    /**
     * Redirige vers la page de connexion si l'utilisateur n'est pas connecté.
     *
     * @param string $redirectTo URL de redirection (relative à BASE_URL).
     */
    public static function requireLogin(string $redirectTo = '/login'): void
    {
        if (!self::isLoggedIn()) {
            header('Location: ' . BASE_URL . $redirectTo);
            exit;
        }
    }

    /**
     * Redirige vers la page de connexion si l'utilisateur n'est pas connecté ou n'est pas admin.
     */
    public static function requireAdmin(): void
    {
        if (!self::isLoggedIn() || !self::isAdmin()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }
}
