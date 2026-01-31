<?php
/**
 * AirBaseManager - Classe de gestion métier
 * Schéma classique : PILOTE, AVION, VOL
 * Inclus : Méthodes pour exercices SQL (q4-q33)
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/Database.php';

class AirBaseManager
{
    private $pdo;

    public function __construct()
    {
        $db = Database::getInstance();
        $this->pdo = $db->getConnection();
    }

    /**
     * Créer les tables de la base de données
     */
    public function createTables(): bool
    {
        try {
            // Table PILOTE (NUMPIL, NOMPIL, ADR, SAL)
            $sql_pilote = "CREATE TABLE IF NOT EXISTS PILOTE (
                NUMPIL INT AUTO_INCREMENT PRIMARY KEY,
                NOMPIL VARCHAR(100) NOT NULL,
                ADR VARCHAR(255),
                SAL DECIMAL(10, 2),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

            // Table AVION (NUMAV, NOMAV, CAP, LOC)
            $sql_avion = "CREATE TABLE IF NOT EXISTS AVION (
                NUMAV INT AUTO_INCREMENT PRIMARY KEY,
                NOMAV VARCHAR(100) NOT NULL,
                CAP INT NOT NULL,
                LOC VARCHAR(100),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

            // Table VOL (NUMVOL, NUMPIL, NUMAV, VILLE_DEP, VILLE_ARR, H_DEP, H_ARR)
            $sql_vol = "CREATE TABLE IF NOT EXISTS VOL (
                NUMVOL INT AUTO_INCREMENT PRIMARY KEY,
                NUMPIL INT,
                NUMAV INT,
                VILLE_DEP VARCHAR(100) NOT NULL,
                VILLE_ARR VARCHAR(100) NOT NULL,
                H_DEP TIME NOT NULL,
                H_ARR TIME NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (NUMPIL) REFERENCES PILOTE(NUMPIL) ON DELETE SET NULL,
                FOREIGN KEY (NUMAV) REFERENCES AVION(NUMAV) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

            // Exécution des requêtes
            $this->pdo->exec($sql_pilote);
            $this->pdo->exec($sql_avion);
            $this->pdo->exec($sql_vol);

            return true;

        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                echo "Erreur création tables : " . $e->getMessage();
            }
            return false;
        }
    }

    /**
     * Insérer des données de test
     */
    public function insertSampleData(): bool
    {
        try {
            // Nettoyage préalable pour éviter les doublons/conflits d'ID
            $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
            $this->pdo->exec("TRUNCATE TABLE VOL");
            $this->pdo->exec("TRUNCATE TABLE PILOTE");
            $this->pdo->exec("TRUNCATE TABLE AVION");
            $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

            // Pilotes
            // IDs spécifiques demandés : 100, 204
            // Noms spécifiques : Dupont, Durand
            // Critères : Habitants Nice/Paris, Salaires variés
            $sql = "INSERT INTO PILOTE (NUMPIL, NOMPIL, ADR, SAL) VALUES
                (1, 'Jean Dupont', '12 Rue de la Paix, Paris', 16000.00),
                (2, 'Sophie Martin', '45 Avenue des Champs, Lyon', 14000.00),
                (3, 'Pierre Durand', '8 Boulevard Victor Hugo, Marseille', 18000.00),
                (4, 'Marie Leclerc', '23 Rue Gambetta, Toulouse', 15500.00),
                (5, 'Luc Bernard', '67 Rue de la République, Nice', 14200.00),
                (100, 'Paul Veran', '10 Promenade des Anglais, Nice', 17000.00),
                (204, 'Julie Roche', '5 Place Masséna, Nice', 16500.00),
                (6, 'Michel Dupont', '4 Rue du Port, Bordeaux', 13000.00), -- Même nom que Jean Dupont
                (7, 'Thomas Petit', '15 Rue de Rivoli, Paris', 19000.00),
                (8, 'Sarah Cohen', '30 Rue d\'Antibes, Cannes', 20000.00)";
            $this->pdo->exec($sql);

            // Avions
            // IDs spécifiques : 100
            // Loc : Nice, Paris
            // Capacité : >300, 350, <350
            $sql = "INSERT INTO AVION (NUMAV, NOMAV, CAP, LOC) VALUES
                (1, 'Airbus A320', 180, 'Paris'),
                (2, 'Boeing 737', 189, 'Lyon'),
                (3, 'Airbus A380', 525, 'Nice'),
                (4, 'Boeing 777', 396, 'Marseille'),
                (5, 'Embraer E190', 114, 'Nice'),
                (100, 'Airbus A350', 350, 'Paris'),
                (6, 'Boeing 747', 416, 'Nice'),
                (7, 'ATR 72', 70, 'Bordeaux')";
            $this->pdo->exec($sql);

            // Vols
            // Départ/Arrivée : Nice, Paris
            // Heures : >18h
            // Pilotes : 100, 204
            $sql = "INSERT INTO VOL (NUMPIL, NUMAV, VILLE_DEP, VILLE_ARR, H_DEP, H_ARR) VALUES
                (1, 1, 'Paris', 'Londres', '08:00:00', '09:30:00'),
                (2, 2, 'Lyon', 'Madrid', '10:15:00', '12:45:00'),
                (3, 3, 'Nice', 'New York', '14:00:00', '22:00:00'), -- Avion > 300 cap
                (4, 4, 'Marseille', 'Rome', '11:30:00', '13:00:00'),
                (5, 5, 'Nice', 'Berlin', '09:45:00', '11:30:00'),
                (100, 100, 'Nice', 'Paris', '19:00:00', '20:30:00'), -- Pilote 100, Dept Nice, >18h, Arrivée Paris
                (204, 6, 'Nice', 'Tokyo', '23:00:00', '11:00:00'), -- Pilote 204, Dept Nice
                (1, 5, 'Paris', 'Nice', '17:00:00', '18:30:00'),
                (5, 1, 'Nice', 'Paris', '07:00:00', '08:30:00'), -- Pilote Niçois, Dept Nice, Avion Paris
                (100, 5, 'Bastia', 'Nice', '12:00:00', '13:00:00'),
                (7, 3, 'Paris', 'Nice', '15:00:00', '16:30:00'), -- Pilote Parisien, Avion Airbus
                (7, 4, 'Paris', 'Dubai', '20:00:00', '06:00:00')";
            $this->pdo->exec($sql);

            return true;

        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                echo "Erreur insertion données : " . $e->getMessage();
            }
            return false;
        }
    }

    // ==================== CRUD PILOTE ====================

    /**
     * Récupérer tous les pilotes
     */
    public function getAllPilotes(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM PILOTE ORDER BY NOMPIL");
        return $stmt->fetchAll();
    }

    /**
     * Récupérer un pilote par numéro
     */
    public function getPiloteByNum(int $numpil): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM PILOTE WHERE NUMPIL = ?");
        $stmt->execute([$numpil]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Ajouter un pilote
     */
    public function addPilote(string $nompil, string $adr, float $sal): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO PILOTE (NOMPIL, ADR, SAL) VALUES (?, ?, ?)");
        return $stmt->execute([$nompil, $adr, $sal]);
    }

    /**
     * Modifier un pilote
     */
    public function updatePilote(int $numpil, string $nompil, string $adr, float $sal): bool
    {
        $stmt = $this->pdo->prepare("UPDATE PILOTE SET NOMPIL = ?, ADR = ?, SAL = ? WHERE NUMPIL = ?");
        return $stmt->execute([$nompil, $adr, $sal, $numpil]);
    }

    /**
     * Supprimer un pilote
     */
    public function deletePilote(int $numpil): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM PILOTE WHERE NUMPIL = ?");
        return $stmt->execute([$numpil]);
    }

    // ==================== CRUD AVION ====================

    /**
     * Récupérer tous les avions
     */
    public function getAllAvions(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM AVION ORDER BY NOMAV");
        return $stmt->fetchAll();
    }

    /**
     * Récupérer un avion par numéro
     */
    public function getAvionByNum(int $numav): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM AVION WHERE NUMAV = ?");
        $stmt->execute([$numav]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Ajouter un avion
     */
    public function addAvion(string $nomav, int $cap, string $loc): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO AVION (NOMAV, CAP, LOC) VALUES (?, ?, ?)");
        return $stmt->execute([$nomav, $cap, $loc]);
    }

    /**
     * Modifier un avion
     */
    public function updateAvion(int $numav, string $nomav, int $cap, string $loc): bool
    {
        $stmt = $this->pdo->prepare("UPDATE AVION SET NOMAV = ?, CAP = ?, LOC = ? WHERE NUMAV = ?");
        return $stmt->execute([$nomav, $cap, $loc, $numav]);
    }

    /**
     * Supprimer un avion
     */
    public function deleteAvion(int $numav): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM AVION WHERE NUMAV = ?");
        return $stmt->execute([$numav]);
    }

    // ==================== CRUD VOL ====================

    /**
     * Récupérer tous les vols
     */
    public function getAllVols(): array
    {
        $sql = "SELECT v.*, 
                       p.NOMPIL,
                       a.NOMAV
                FROM VOL v
                LEFT JOIN PILOTE p ON v.NUMPIL = p.NUMPIL
                LEFT JOIN AVION a ON v.NUMAV = a.NUMAV
                ORDER BY v.H_DEP";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer un vol par numéro
     */
    public function getVolByNum(int $numvol): ?array
    {
        $sql = "SELECT v.*, 
                       p.NOMPIL,
                       a.NOMAV
                FROM VOL v
                LEFT JOIN PILOTE p ON v.NUMPIL = p.NUMPIL
                LEFT JOIN AVION a ON v.NUMAV = a.NUMAV
                WHERE v.NUMVOL = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$numvol]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Ajouter un vol
     */
    public function addVol(int $numpil, int $numav, string $ville_dep, string $ville_arr, string $h_dep, string $h_arr): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO VOL (NUMPIL, NUMAV, VILLE_DEP, VILLE_ARR, H_DEP, H_ARR) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$numpil, $numav, $ville_dep, $ville_arr, $h_dep, $h_arr]);
    }

    /**
     * Modifier un vol
     */
    public function updateVol(int $numvol, int $numpil, int $numav, string $ville_dep, string $ville_arr, string $h_dep, string $h_arr): bool
    {
        $stmt = $this->pdo->prepare("UPDATE VOL SET NUMPIL = ?, NUMAV = ?, VILLE_DEP = ?, VILLE_ARR = ?, H_DEP = ?, H_ARR = ? WHERE NUMVOL = ?");
        return $stmt->execute([$numpil, $numav, $ville_dep, $ville_arr, $h_dep, $h_arr, $numvol]);
    }

    /**
     * Supprimer un vol
     */
    public function deleteVol(int $numvol): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM VOL WHERE NUMVOL = ?");
        return $stmt->execute([$numvol]);
    }

    // ==================== REQUÊTES AVANCÉES ====================

    /**
     * Statistiques générales
     */
    public function getStatistics(): array
    {
        $stats = [];

        $stats['total_pilotes'] = $this->pdo->query("SELECT COUNT(*) FROM PILOTE")->fetchColumn();
        $stats['total_avions'] = $this->pdo->query("SELECT COUNT(*) FROM AVION")->fetchColumn();
        $stats['total_vols'] = $this->pdo->query("SELECT COUNT(*) FROM VOL")->fetchColumn();
        $stats['salaire_moyen'] = $this->pdo->query("SELECT AVG(SAL) FROM PILOTE")->fetchColumn();

        return $stats;
    }

    /**
     * Vols d'un pilote
     */
    public function getVolsByPilote(int $numpil): array
    {
        $sql = "SELECT v.*, a.NOMAV 
                FROM VOL v
                LEFT JOIN AVION a ON v.NUMAV = a.NUMAV
                WHERE v.NUMPIL = ?
                ORDER BY v.H_DEP";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$numpil]);
        return $stmt->fetchAll();
    }

    /**
     * Vols d'un avion
     */
    public function getVolsByAvion(int $numav): array
    {
        $sql = "SELECT v.*, p.NOMPIL 
                FROM VOL v
                LEFT JOIN PILOTE p ON v.NUMPIL = p.NUMPIL
                WHERE v.NUMAV = ?
                ORDER BY v.H_DEP";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$numav]);
        return $stmt->fetchAll();
    }

    /**
     * Vols au départ d'une ville
     */
    public function getVolsByVilleDepart(string $ville): array
    {
        $sql = "SELECT v.*, p.NOMPIL, a.NOMAV 
                FROM VOL v
                LEFT JOIN PILOTE p ON v.NUMPIL = p.NUMPIL
                LEFT JOIN AVION a ON v.NUMAV = a.NUMAV
                WHERE v.VILLE_DEP = ?
                ORDER BY v.H_DEP";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$ville]);
        return $stmt->fetchAll();
    }

    /**
     * Vols à destination d'une ville
     */
    public function getVolsByVilleArrivee(string $ville): array
    {
        $sql = "SELECT v.*, p.NOMPIL, a.NOMAV 
                FROM VOL v
                LEFT JOIN PILOTE p ON v.NUMPIL = p.NUMPIL
                LEFT JOIN AVION a ON v.NUMAV = a.NUMAV
                WHERE v.VILLE_ARR = ?
                ORDER BY v.H_DEP";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$ville]);
        return $stmt->fetchAll();
    }

    // ==================== EXERCICES SQL (Q4 - Q33) ====================

    public function q4()
    {
        return $this->pdo->query("SELECT * FROM AVION WHERE CAP > 350")->fetchAll();
    }


    public function q5()
    {
        return $this->pdo->query("SELECT NUMAV, NOMAV FROM AVION WHERE LOC = 'Nice'")->fetchAll();
    }


    public function q6()
    {
        return $this->pdo->query("SELECT p.NUMPIL, v.VILLE_DEP FROM PILOTE p JOIN VOL v ON p.NUMPIL = v.NUMPIL")->fetchAll();
    }



    public function q7()
    {
        return $this->pdo->query("SELECT * FROM PILOTE")->fetchAll();
    }



    public function q8()
    {
        return $this->pdo->query("SELECT NOMPIL FROM PILOTE WHERE ADR LIKE '%Paris%' AND SAL > 15000")->fetchAll();
    }



    public function q9()
    {
        return $this->pdo->query("SELECT * FROM VOL ORDER BY H_DEP")->fetchAll();
    }
    public function q10()
    {
        return $this->pdo->query("SELECT * FROM PILOTE ORDER BY SAL DESC")->fetchAll();
    }


    public function q11()
    {
        return $this->pdo->query("SELECT NUMAV, NOMAV FROM AVION WHERE LOC = 'Nice' OR CAP < 350")->fetchAll();
    }


    public function q12()
    {
        return $this->pdo->query("SELECT * FROM VOL WHERE VILLE_DEP = 'Nice' AND VILLE_ARR = 'Paris' AND H_DEP > '18:00:00'")->fetchAll();
    }


    public function q13()
    {
        return $this->pdo->query("SELECT NUMPIL FROM PILOTE WHERE NUMPIL NOT IN (SELECT DISTINCT NUMPIL FROM VOL)")->fetchAll();
    }


    public function q14()
    {
        return $this->pdo->query("SELECT DISTINCT NUMPIL FROM VOL")->fetchAll();
    }


    public function q15()
    {
        return $this->pdo->query("SELECT NUMVOL, VILLE_DEP FROM VOL WHERE NUMPIL IN (100, 204)")->fetchAll();
    }



    public function q16()
    {
        return $this->pdo->query("SELECT v.NUMVOL FROM VOL v JOIN PILOTE p ON v.NUMPIL = p.NUMPIL WHERE v.VILLE_DEP = 'Nice' AND p.ADR LIKE '%Nice%'")->fetchAll();
    }

    
    public function q17()
    {
        return $this->pdo->query("SELECT v.* FROM VOL v JOIN AVION a ON v.NUMAV = a.NUMAV WHERE a.LOC != 'Nice'")->fetchAll();
    }
    public function q18()
    {
        return $this->pdo->query("SELECT DISTINCT p.NUMPIL, p.NOMPIL FROM PILOTE p JOIN VOL v ON p.NUMPIL = v.NUMPIL JOIN AVION a ON v.NUMAV = a.NUMAV WHERE v.VILLE_DEP = 'Nice' AND a.CAP > 300")->fetchAll();
    }
    public function q19()
    {
        return $this->pdo->query("SELECT DISTINCT p.NOMPIL FROM PILOTE p JOIN VOL v ON p.NUMPIL = v.NUMPIL JOIN AVION a ON v.NUMAV = a.NUMAV WHERE p.ADR LIKE '%Paris%' AND v.VILLE_DEP = 'Nice' AND a.NOMAV LIKE 'Airbus%'")->fetchAll();
    }
    public function q20()
    {
        return $this->pdo->query("SELECT v.* FROM VOL v JOIN PILOTE p ON v.NUMPIL = p.NUMPIL JOIN AVION a ON v.NUMAV = a.NUMAV WHERE p.ADR LIKE '%Nice%' AND (v.VILLE_DEP = 'Nice' OR v.VILLE_ARR = 'Nice') AND a.LOC = 'Paris'")->fetchAll();
    }
    public function q21()
    {
        return $this->pdo->query("SELECT COUNT(*) as total FROM PILOTE")->fetchAll();
    }
    public function q22()
    {
        return $this->pdo->query("SELECT SUBSTRING_INDEX(ADR, ' ', -1) as ville, AVG(SAL) as salaire_moyen FROM PILOTE GROUP BY SUBSTRING_INDEX(ADR, ' ', -1)")->fetchAll();
    }
    public function q23()
    {
        return $this->pdo->query("SELECT VILLE_DEP, COUNT(*) as nombre_vols FROM VOL GROUP BY VILLE_DEP")->fetchAll();
    }
    public function q24()
    {
        return $this->pdo->query("SELECT p.*, COUNT(v.NUMVOL) as nb_vols FROM PILOTE p JOIN VOL v ON p.NUMPIL = v.NUMPIL GROUP BY p.NUMPIL HAVING nb_vols > 2")->fetchAll();
    }
    public function q25()
    {
        return $this->pdo->query("SELECT VILLE_DEP, COUNT(*) as nb_vols FROM VOL GROUP BY VILLE_DEP HAVING nb_vols > (SELECT AVG(c) FROM (SELECT COUNT(*) as c FROM VOL GROUP BY VILLE_DEP) as sub)")->fetchAll();
    }
    public function q26()
    {
        return $this->pdo->query("SELECT * FROM PILOTE WHERE SAL > (SELECT AVG(SAL) FROM PILOTE)")->fetchAll();
    }
    public function q27()
    {
        return $this->pdo->query("SELECT * FROM AVION WHERE NUMAV NOT IN (SELECT DISTINCT NUMAV FROM VOL)")->fetchAll();
    }
    public function q28()
    {
        return $this->pdo->query("SELECT DISTINCT p.* FROM PILOTE p JOIN VOL v ON p.NUMPIL = v.NUMPIL WHERE v.VILLE_DEP = 'Nice' AND p.NUMPIL NOT IN (SELECT NUMPIL FROM VOL WHERE VILLE_DEP != 'Nice')")->fetchAll();
    }
    public function q29()
    {
        return $this->pdo->query("SELECT v.*, p.NOMPIL, p.ADR FROM VOL v JOIN PILOTE p ON v.NUMPIL = p.NUMPIL WHERE SUBSTRING_INDEX(p.ADR, ' ', -1) IN (SELECT DISTINCT SUBSTRING_INDEX(ADR, ' ', -1) FROM PILOTE WHERE NOMPIL LIKE '%Dupont%')")->fetchAll();
    }
    public function q30()
    {
        return $this->pdo->query("SELECT p.NOMPIL, COUNT(v.NUMVOL) as nb_vols FROM PILOTE p JOIN VOL v ON p.NUMPIL = v.NUMPIL GROUP BY p.NUMPIL HAVING nb_vols > (SELECT COUNT(*) / COUNT(DISTINCT NUMPIL) FROM VOL)")->fetchAll();
    }
    public function q31()
    {
        return $this->pdo->query("SELECT DISTINCT v.NUMPIL FROM VOL v WHERE v.NUMPIL NOT IN (SELECT NUMPIL FROM PILOTE WHERE NOMPIL LIKE '%Durand%')")->fetchAll();
    }
    public function q32()
    {
        return $this->pdo->query("SELECT DISTINCT v2.VILLE_ARR FROM VOL v1 JOIN VOL v2 ON v1.VILLE_ARR = v2.VILLE_DEP WHERE v1.VILLE_DEP = 'Paris'")->fetchAll();
    }
    public function q33()
    {
        return $this->pdo->query("SELECT NUMAV, NOMAV, LOC FROM AVION WHERE LOC = (SELECT LOC FROM AVION WHERE NUMAV = 100 LIMIT 1)")->fetchAll();
    }
}
