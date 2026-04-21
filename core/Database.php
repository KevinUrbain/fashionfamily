<?php
class Database
{
    private static ?PDO $instance = null;

    /**
     * Empêche l'instanciation directe (pattern Singleton).
     */
    private function __construct() {}

    /**
     * Empêche le clonage de l'instance (pattern Singleton).
     */
    private function __clone() {}

    /**
     * Retourne l'instance PDO unique, en la créant à la première demande.
     * Configure le mode d'erreur en exceptions, le fetch en tableaux associatifs
     * et désactive les requêtes préparées émulées.
     *
     * @return PDO
     * @throws \PDOException Si la connexion à la base de données échoue.
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                if (ENVIRONMENT === 'development') {
                    die('Erreur de connexion BDD : ' . $e->getMessage());
                }
                die('Une erreur est survenue. Veuillez réessayer plus tard.');
            }
        }
        return self::$instance;
    }
}
