<?php
/**
 * AirBase Manager - Système de Gestion de Vols
 * Schéma : PILOTE, AVION, VOL
 */

require_once 'config/config.php';
require_once 'config/Database.php';
require_once 'classes/AirBaseManager.php';

$manager = new AirBaseManager();
$action = $_GET['action'] ?? 'home';

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AirBase Manager - Gestion de Vols</title>
    <style>
        /* Variables de couleurs */
        :root {
            --primary-blue: #4a90e2;
            /* Bleu clair principal */
            --light-blue: #ebf5fb;
            /* Fond bleu très clair */
            --dark-text: #2c3e50;
            /* Noir/Gris foncé pour texte */
            --white: #ffffff;
            --grey-border: #e1e4e8;
            --hover-blue: #357abd;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', 'Roboto', Helvetica, Arial, sans-serif;
            background-color: #f5f7fa;
            /* Fond gris très léger */
            color: var(--dark-text);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: var(--white);
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            /* Ombre douce */
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        h1 {
            color: var(--primary-blue);
            margin-bottom: 5px;
            text-align: center;
            font-weight: 300;
            font-size: 2.5rem;
            letter-spacing: 1px;
        }

        .subtitle {
            text-align: center;
            color: #7f8c8d;
            margin-bottom: 40px;
            font-weight: 400;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 2px;
        }

        /* Navigation */
        .nav {
            display: flex;
            gap: 15px;
            margin-bottom: 40px;
            flex-wrap: wrap;
            justify-content: center;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--grey-border);
        }

        .btn {
            padding: 12px 25px;
            background: var(--white);
            color: var(--dark-text);
            text-decoration: none;
            border-radius: 6px;
            border: 1px solid var(--grey-border);
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 15px;
            font-weight: 500;
        }

        .btn:hover {
            background: var(--light-blue);
            color: var(--primary-blue);
            border-color: var(--primary-blue);
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(74, 144, 226, 0.2);
        }

        /* États actifs ou boutons spéciaux */
        .btn-success {
            background: var(--white);
            color: #27ae60;
            border-color: #27ae60;
        }

        .btn-success:hover {
            background: #eafaf1;
            color: #219150;
        }

        .btn-ex {
            background: var(--primary-blue);
            color: var(--white);
            border-color: var(--primary-blue);
        }

        .btn-ex:hover {
            background: var(--hover-blue);
            color: var(--white);
        }

        /* Tableaux */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            background: var(--white);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        }

        th {
            background: var(--primary-blue);
            color: var(--white);
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            padding: 15px;
            text-align: left;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            color: #555;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background-color: var(--light-blue);
        }

        /* Cartes stats */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
            margin: 30px 0;
        }

        .stat-card {
            background: var(--white);
            color: var(--dark-text);
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            border: 1px solid var(--grey-border);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-blue);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .stat-card h3 {
            font-size: 2.5em;
            margin-bottom: 10px;
            color: var(--primary-blue);
            font-weight: 300;
        }

        .stat-card p {
            font-size: 1rem;
            color: #7f8c8d;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
        }

        /* Alertes et boites */
        .info-box {
            background: var(--light-blue);
            padding: 30px;
            border-radius: 8px;
            margin-top: 40px;
            border-left: 4px solid var(--primary-blue);
        }

        .info-box h3 {
            color: var(--primary-blue);
            margin-bottom: 20px;
            font-size: 1.2rem;
            text-align: left;
        }

        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
        }

        .alert-success {
            background-color: #eafaf1;
            color: #27ae60;
            border: 1px solid #d5f5e3;
        }

        /* Titres de section */
        h2 {
            border-bottom: 2px solid var(--light-blue);
            padding-bottom: 10px;
            margin-top: 30px;
            color: var(--dark-text);
            font-weight: 400;
        }

        strong {
            color: var(--dark-text);
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>✈️ AirBase Manager</h1>
        <p class="subtitle">Système de Gestion de Vols</p>

        <div class="nav">
            <a href="?action=home" class="btn">🏠 Accueil</a>
            <a href="?action=init" class="btn btn-success">⚙️ Initialiser DB</a>
            <a href="?action=pilotes" class="btn">👨‍✈️ Pilotes</a>
            <a href="?action=avions" class="btn">🛩️ Avions</a>
            <a href="?action=vols" class="btn">📋 Vols</a>
            <a href="?action=exercices" class="btn btn-ex">📚 Exercices SQL</a>
        </div>

        <?php
        // Gestion des actions
        switch ($action) {
            case 'init':
                echo '<div class="alert alert-success">';
                if ($manager->createTables()) {
                    echo '✅ Tables créées avec succès !<br>';
                    if ($manager->insertSampleData()) {
                        echo '✅ Données de test insérées !';
                    }
                } else {
                    echo '❌ Erreur lors de la création des tables.';
                }
                echo '</div>';

                $stats = $manager->getStatistics();
                ?>
                <div class="stats">
                    <div class="stat-card">
                        <h3><?= $stats['total_pilotes'] ?></h3>
                        <p>Pilotes</p>
                    </div>
                    <div class="stat-card">
                        <h3><?= $stats['total_avions'] ?></h3>
                        <p>Avions</p>
                    </div>
                    <div class="stat-card">
                        <h3><?= $stats['total_vols'] ?></h3>
                        <p>Vols</p>
                    </div>
                    <div class="stat-card">
                        <h3><?= number_format($stats['salaire_moyen'], 2) ?> €</h3>
                        <p>Salaire Moyen</p>
                    </div>
                </div>
                <?php
                break;

            case 'pilotes':
                $pilotes = $manager->getAllPilotes();
                echo '<h2>👨‍✈️ Liste des Pilotes</h2>';
                echo '<table>';
                echo '<tr><th>NUMPIL</th><th>Nom</th><th>Adresse</th><th>Salaire</th></tr>';
                foreach ($pilotes as $pilote) {
                    echo "<tr>";
                    echo "<td><strong>{$pilote['NUMPIL']}</strong></td>";
                    echo "<td>{$pilote['NOMPIL']}</td>";
                    echo "<td>{$pilote['ADR']}</td>";
                    echo "<td>" . number_format($pilote['SAL'], 2) . " €</td>";
                    echo "</tr>";
                }
                echo '</table>';
                break;

            case 'avions':
                $avions = $manager->getAllAvions();
                echo '<h2>🛩️ Flotte d\'Avions</h2>';
                echo '<table>';
                echo '<tr><th>NUMAV</th><th>Nom</th><th>Capacité</th><th>Localisation</th></tr>';
                foreach ($avions as $avion) {
                    echo "<tr>";
                    echo "<td><strong>{$avion['NUMAV']}</strong></td>";
                    echo "<td>{$avion['NOMAV']}</td>";
                    echo "<td>{$avion['CAP']} passagers</td>";
                    echo "<td>{$avion['LOC']}</td>";
                    echo "</tr>";
                }
                echo '</table>';
                break;

            case 'vols':
                $vols = $manager->getAllVols();
                echo '<h2>📋 Planning des Vols</h2>';
                echo '<table>';
                echo '<tr><th>NUMVOL</th><th>Pilote</th><th>Avion</th><th>Départ</th><th>Arrivée</th><th>Heure Départ</th><th>Heure Arrivée</th></tr>';
                foreach ($vols as $vol) {
                    echo "<tr>";
                    echo "<td><strong>{$vol['NUMVOL']}</strong></td>";
                    echo "<td>" . ($vol['NOMPIL'] ?? 'N/A') . "</td>";
                    echo "<td>" . ($vol['NOMAV'] ?? 'N/A') . "</td>";
                    echo "<td>{$vol['VILLE_DEP']}</td>";
                    echo "<td>{$vol['VILLE_ARR']}</td>";
                    echo "<td>" . substr($vol['H_DEP'], 0, 5) . "</td>";
                    echo "<td>" . substr($vol['H_ARR'], 0, 5) . "</td>";
                    echo "</tr>";
                }
                echo '</table>';
                break;

            case 'exercices':
                include 'views/pages/exercices.php';
                break;

            default:
                $stats = $manager->getStatistics();
                ?>
                <h2>📊 Tableau de Bord</h2>
                <div class="stats">
                    <div class="stat-card">
                        <h3><?= $stats['total_pilotes'] ?></h3>
                        <p>Pilotes</p>
                    </div>
                    <div class="stat-card">
                        <h3><?= $stats['total_avions'] ?></h3>
                        <p>Avions</p>
                    </div>
                    <div class="stat-card">
                        <h3><?= $stats['total_vols'] ?></h3>
                        <p>Vols Programmés</p>
                    </div>
                    <div class="stat-card">
                        <h3><?= number_format($stats['salaire_moyen'], 2) ?> €</h3>
                        <p>Salaire Moyen</p>
                    </div>
                </div>

                <div class="info-box">
                    <h3>🚀 Démarrage Rapide</h3>
                    <ol>
                        <li>Cliquez sur <strong>"Initialiser DB"</strong> pour créer les tables et insérer les données de test
                        </li>
                        <li>Explorez les sections : <strong>Pilotes</strong>, <strong>Avions</strong>, <strong>Vols</strong>
                        </li>
                        <li>Utilisez la classe <code>AirBaseManager</code> pour vos requêtes personnalisées</li>
                    </ol>

                    <h3 style="margin-top: 20px;">📋 Structure de la Base</h3>
                    <ul style="margin-left: 20px; line-height: 1.8;">
                        <li><strong>PILOTE</strong> : NUMPIL, NOMPIL, ADR, SAL</li>
                        <li><strong>AVION</strong> : NUMAV, NOMAV, CAP, LOC</li>
                        <li><strong>VOL</strong> : NUMVOL, NUMPIL, NUMAV, VILLE_DEP, VILLE_ARR, H_DEP, H_ARR</li>
                    </ul>
                </div>
                <?php
                break;
        }
        ?>
    </div>
</body>

</html>