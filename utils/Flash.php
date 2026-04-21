<?php
class Flash
{
    /**
     * Stocke un message flash en session pour un affichage unique à la prochaine requête.
     *
     * @param string $type    Catégorie du message (ex. "success", "error").
     * @param string $message Contenu du message à afficher.
     */
    public static function set(string $type, string $message): void
    {
        Session::setFlash($type, $message);
    }

    /**
     * Récupère et supprime un message flash de la session.
     *
     * @param string $type Catégorie du message à récupérer.
     * @return string|null Le message ou null s'il n'existe pas.
     */
    public static function get(string $type): ?string
    {
        return Session::getFlash($type);
    }

    /**
     * Vérifie si un message flash d'un type donné est en attente en session.
     *
     * @param string $type Catégorie du message.
     * @return bool true si un message de ce type est présent.
     */
    public static function has(string $type): bool
    {
        return Session::hasFlash($type);
    }
}
