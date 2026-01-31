<?php
/**
 * Page d'affichage des Exercices SQL (Q4 - Q33)
 */

$questions = [
    4 => "Donner la liste des avions dont la capacité est supérieure à 350.",
    5 => "Donner les numéros et noms des avions localisés à Nice.",
    6 => "Donner les numéros des pilotes en service et les villes de départ de leurs vols.",
    7 => "Donner toutes les informations sur les pilotes.",
    8 => "Donner le nom des pilotes domiciliés à Paris avec un salaire > 15000.",
    9 => "Donner les vols triés par heure de départ.",
    10 => "Donner les pilotes triés par salaire décroissant.",
    11 => "Donner les avions (numéro et nom) localisés à Nice ou dont la capacité est inférieure à 350 passagers.",
    12 => "Donner les vols au départ de Nice allant à Paris après 18 heures.",
    13 => "Quels sont les numéros des pilotes qui ne sont pas en service ?",
    14 => "Quels sont les numéros des pilotes qui sont en service ?",
    15 => "Donner les vols (numéro, ville de départ) effectués par les pilotes de numéro 100 et 204.",
    16 => "Donnez le numéro des vols effectués au départ de Nice par des pilotes Niçois.",
    17 => "Donner les vols effectués par un avion non localisé à Nice.",
    18 => "Donner les pilotes (numéro et nom) assurant au moins un vol au départ de Nice avec un avion de capacité supérieure à 300 places.",
    19 => "Donner les noms des pilotes parisiens assurant un vol au départ de Nice avec un Airbus.",
    20 => "Donner les vols effectués par un pilote Niçois au départ ou à l’arrivée de Nice avec un avion localisé à Paris.",
    21 => "Donner le nombre total de pilotes.",
    22 => "Donner le salaire moyen des pilotes par ville.",
    23 => "Donner le nombre de vols par ville de départ.",
    24 => "Donner les pilotes ayant assuré plus de 2 vols.",
    25 => "Donner les villes dont le nombre de vols est supérieur à la moyenne.",
    26 => "Donner les pilotes dont le salaire est supérieur au salaire moyen.",
    27 => "Donner les avions jamais utilisés.",
    28 => "Donner les pilotes ayant assuré uniquement des vols au départ de Nice.",
    29 => "Donner les vols effectués par des pilotes habitant la même ville que le pilote Dupont.",
    30 => "Donner les pilotes ayant assuré plus de vols que la moyenne.",
    31 => "Donner les numéros des pilotes en service différents de celui de Durand.",
    32 => "Donner les villes desservies à partir de la ville d'arrivée d'un vol au départ de Paris.",
    33 => "Donner les appareils (leur numéro) localisés dans la même ville que l'avion numéro 100."
];

echo '<h2>Exercices SQL (TP)</h2>';
echo '<div class="exercises-container">';

foreach ($questions as $id => $question) {
    $method = "q$id";
    $result = [];
    $error = null;

    try {
        if (method_exists($manager, $method)) {
            $result = $manager->$method();
        } else {
            $error = "Méthode $method non implémentée.";
        }
    } catch (Exception $e) {
        $error = "Erreur SQL : " . $e->getMessage();
    }

    echo "<div class='exercise-card' id='q$id'>";
    echo "<h3>Question $id</h3>";
    echo "<p class='question-text'>$question</p>";

    if ($error) {
        echo "<div class='alert alert-error'>$error</div>";
    } elseif (empty($result)) {
        echo "<div class='alert alert-warning'>Aucun résultat trouvé.</div>";
    } else {
        // Affichage dynamique du tableau de résultats
        echo "<table class='result-table'>";

        // En-têtes (clés du premier élément)
        echo "<thead><tr>";
        $columns = array_keys($result[0]);
        foreach ($columns as $col) {
            echo "<th>$col</th>"; // Affiche brut (ex: NUMPIL, NOMPIL...)
        }
        echo "</tr></thead>";

        // Données
        echo "<tbody>";
        foreach ($result as $row) {
            echo "<tr>";
            foreach ($row as $cell) {
                echo "<td>$cell</td>";
            }
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";

        // Compteur de résultats
        echo "<div class='result-count'>" . count($result) . " résultat(s)</div>";
    }
    echo "</div>";
}

echo '</div>';

// CSS spécifique pour cette page
?>
<style>
    .exercises-container {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .exercise-card {
        background: var(--white);
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
        border: 1px solid var(--grey-border);
        border-left: 4px solid var(--primary-blue);
        transition: transform 0.3s ease;
    }

    .exercise-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.05);
    }

    .question-text {
        font-size: 1.1em;
        color: #555;
        margin-bottom: 25px;
        font-weight: 400;
        line-height: 1.6;
    }

    .result-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.95em;
        margin-top: 15px;
    }

    .result-table th {
        background: var(--light-blue);
        /* Fond clair pour les headers dans les cartes */
        color: var(--primary-blue);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8em;
        padding: 12px;
        border-bottom: 2px solid var(--primary-blue);
    }

    .result-table td {
        padding: 12px;
        border-bottom: 1px solid #eee;
        color: #444;
    }

    .result-table tr:hover {
        background-color: #f9fbfd;
    }

    .result-count {
        text-align: right;
        font-size: 0.85em;
        color: #95a5a6;
        margin-top: 15px;
        font-style: italic;
    }

    .alert-warning {
        background: #fff8e1;
        color: #f39c12;
        border: 1px solid #ffe0b2;
        padding: 15px;
        border-radius: 6px;
        font-size: 0.9em;
    }

    .alert-error {
        background: #fdedec;
        color: #e74c3c;
        border: 1px solid #fadbd8;
        padding: 15px;
        border-radius: 6px;
    }

    h3 {
        color: var(--primary-blue);
        margin-bottom: 10px;
        font-weight: 500;
    }
</style>