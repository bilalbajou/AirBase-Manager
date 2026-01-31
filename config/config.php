<?php
/**
 * Configuration globale de l'application
 */

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'airbase_manager');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_CHARSET', 'utf8mb4');

// Mode debug
define('DEBUG_MODE', true);

// Gestion des erreurs
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
