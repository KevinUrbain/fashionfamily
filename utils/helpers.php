<?php
/**
 * Fonctions globales pour les vues
 * Ces fonctions délèguent aux classes utilitaires
 */

/**
 * Échappe les caractères HTML d'une chaîne pour une sortie sécurisée dans les vues.
 *
 * @param string $str Chaîne à échapper.
 * @return string Chaîne avec les caractères HTML encodés.
 */
function escape(string $str): string
{
    return Sanitizer::escape($str);
}

/**
 * Génère ou récupère le token CSRF de la session courante.
 *
 * @return string Token CSRF hexadécimal de 64 caractères.
 */
function generateCsrfToken(): string
{
    return Session::generateCsrfToken();
}

/**
 * Récupère et supprime un message flash de la session.
 *
 * @param string $type Catégorie du message (ex. "success", "error").
 * @return string|null Le message ou null s'il n'existe pas.
 */
function getFlashMessage(string $type): ?string
{
    return Session::getFlash($type);
}

/**
 * Vérifie si un message flash d'un type donné est en attente en session.
 *
 * @param string $type Catégorie du message.
 * @return bool true si un message de ce type est présent.
 */
function hasFlashMessage(string $type): bool
{
    return Session::hasFlash($type);
}

/**
 * Indique si un utilisateur est actuellement connecté.
 *
 * @return bool true si la session contient un identifiant utilisateur.
 */
function isLoggedIn(): bool
{
    return Auth::isLoggedIn();
}

/**
 * Indique si l'utilisateur connecté possède le rôle admin.
 *
 * @return bool true si le rôle en session est "admin".
 */
function isAdmin(): bool
{
    return Auth::isAdmin();
}

/**
 * Écrit une entrée horodatée dans le fichier de log de l'application.
 *
 * @param string $message Contenu du message à journaliser.
 * @param string $level   Niveau de sévérité (ex. "info", "error", "warning").
 */
function logMessage(string $message, string $level = 'info'): void
{
    $logFile  = LOGS_PATH . '/app.log';
    $timestamp = date('Y-m-d H:i:s');
    $entry    = "[$timestamp] [$level] $message" . PHP_EOL;
    file_put_contents($logFile, $entry, FILE_APPEND);
}
