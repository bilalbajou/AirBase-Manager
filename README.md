# AirBase Manager

**AirBase Manager** est une application web PHP simple et moderne destinée à la gestion de bases aériennes, de pilotes, d'avions et de vols. Elle a été conçue comme un outil pédagogique pour pratiquer le développement PHP et les requêtes SQL complexes.

## Fonctionnalités

*   **Gestion des Pilotes** : Liste détaillée avec grades, adresses et salaires.
*   **Gestion des Avions** : Suivi de la flotte, capacités et localisations.
*   **Gestion des Vols** : Planning des vols avec villes de départ et d'arrivée.
*   **Tableau de Bord** : Statistiques en temps réel sur l'activité de la base.
*   **Exercices SQL** : Une section dédiée intégrant 30 requêtes SQL pré-programmées pour l'analyse de données (jointures, agrégations, filtres).
*   **Interface Moderne** : UI épurée en bleu et blanc pour une expérience utilisateur agréable.

## Prérequis

*   **Serveur Web** : Apache ou Nginx (inclus dans WAMP/XAMPP).
*   **PHP** : Version 7.4 ou supérieure.
*   **Base de Données** : MySQL ou MariaDB.
*   **Extensions PHP** : PDO activé.

## Installation

### 1. Cloner le projet
```bash
git clone https://github.com/bilalbajou/AirBase-Manager.git
cd "AirBase Manager"
```

### 2. Configuration
L'application est configurée par défaut pour fonctionner avec WAMP (localhost, root, sans mot de passe).
Si nécessaire, modifiez le fichier `config/config.php` pour adapter les identifiants de base de données :

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'airbase_manager');
define('DB_USER', 'root');
define('DB_PASS', ''); // Mettez votre mot de passe ici si nécessaire
```

### 3. Lancer l'application
Si vous utilisez WAMP, placez simplement le dossier dans `www` et accédez à `http://localhost/AirBase Manager`.

Sinon, vous pouvez utiliser le serveur interne de PHP pour tester rapidement :
```bash
php -S localhost:8000
```
Puis ouvrez `http://localhost:8000` dans votre navigateur.

## Initialisation de la Base de Données

Une fois l'application lancée :
1.  Cliquez sur le bouton vert **" Initialiser DB"** sur la page d'accueil.
2.  Cela créera automatiquement les tables (`PILOTE`, `AVION`, `VOL`) et insérera les données de test nécessaires pour les exercices.

## Structure du Projet

```
AirBase Manager/
├── assets/              # Ressources statiques (CSS, JS, Images)
├── classes/             # Classes PHP (Logique métier)
│   ├── AirBaseManager.php # Gestionnaire principal (CRUD + Requêtes SQL)
│   └── Helper.php       # Utilitaires
├── config/              # Configuration
│   ├── config.php       # Constantes globales
│   └── Database.php     # Connexion BDD (Singleton PDO)
├── views/               # Vues HTML
│   ├── layout/          # Header, Footer
│   └── pages/           # Pages spécifiques (Accueil, Exercices...)
├── index.php            # Point d'entrée (Routeur)
└── README.md            # Documentation
```

## Auteur

Développé par **Bilal Bajou**.

## Licence

Ce projet est sous licence MIT.
