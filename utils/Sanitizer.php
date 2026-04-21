<?php
class Sanitizer
{
    /**
     * Échappe les caractères HTML (protection XSS)
     */
    public static function escape(string $data): string
    {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Nettoie une chaîne (trim + strip_tags)
     */
    public static function clean(string $data): string
    {
        return trim(strip_tags($data));
    }
}
