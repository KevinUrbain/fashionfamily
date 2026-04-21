<?php
class Session
{
    /**
     * Démarre la session PHP si elle n'est pas déjà active.
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Stocke une valeur en session sous la clé donnée.
     *
     * @param string $key   Nom de la variable de session.
     * @param mixed  $value Valeur à stocker.
     */
    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Récupère une valeur de session, ou la valeur par défaut si absente.
     *
     * @param string $key     Nom de la variable de session.
     * @param mixed  $default Valeur retournée si la clé n'existe pas.
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Vérifie si une clé existe en session.
     *
     * @param string $key Nom de la variable de session.
     * @return bool true si la clé est définie.
     */
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Supprime une variable de session.
     *
     * @param string $key Nom de la variable à supprimer.
     */
    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Détruit complètement la session et invalide le cookie de session.
     */
    public static function destroy(): void
    {
        session_unset();
        session_destroy();
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
    }

    /**
     * Génère et stocke un token CSRF en session, ou retourne le token existant.
     *
     * @return string Token CSRF hexadécimal de 64 caractères.
     */
    public static function generateCsrfToken(): string
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Vérifie qu'un token CSRF correspond à celui stocké en session (comparaison sécurisée).
     *
     * @param string $token Token soumis par le formulaire.
     * @return bool true si le token est valide.
     */
    public static function verifyCsrfToken(string $token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Stocke un message flash en session pour un affichage unique à la prochaine requête.
     *
     * @param string $type    Catégorie du message (ex. "success", "error").
     * @param string $message Contenu du message.
     */
    public static function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'][$type] = $message;
    }

    /**
     * Récupère et supprime un message flash de la session.
     *
     * @param string $type Catégorie du message à récupérer.
     * @return string|null Le message ou null s'il n'existe pas.
     */
    public static function getFlash(string $type): ?string
    {
        if (isset($_SESSION['flash'][$type])) {
            $message = $_SESSION['flash'][$type];
            unset($_SESSION['flash'][$type]);
            return $message;
        }
        return null;
    }

    /**
     * Vérifie si un message flash d'un type donné est en attente en session.
     *
     * @param string $type Catégorie du message.
     * @return bool true si un message de ce type est présent.
     */
    public static function hasFlash(string $type): bool
    {
        return isset($_SESSION['flash'][$type]);
    }
}
