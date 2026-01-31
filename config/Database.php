<?php
/**
 * Classe Database - Gestion de la connexion PDO
 * Pattern Singleton pour une seule instance de connexion
 */

class Database
{
    private static $instance = null;
    private $pdo;

    /**
     * Constructeur privé (Singleton)
     */
    private function __construct()
    {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                die("Erreur de connexion : " . $e->getMessage());
            } else {
                die("Erreur de connexion à la base de données.");
            }
        }
    }

    /**
     * Récupérer l'instance unique (Singleton)
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Récupérer l'objet PDO
     */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    /**
     * Empêcher le clonage
     */
    private function __clone() {}

    /**
     * Empêcher la désérialisation
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }
}
