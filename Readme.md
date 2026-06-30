# Horizons Lointains
Site web d'agence de voyage permettant aux clients de découvrir des séjours, effectuer des réservations et suivre leur dossier. Développé en PHP, MySQL et MongoDB.

## Environnement de travail
- XAMPP (Apache + MySQL)
- PHP 8.2
- Composer
- Git
- Extension PHP MongoDB
- Un compte MongoDB Atlas

## Installation

1. Cloner le projet :
git clone https://github.com/Rika600/horizons-lointains.git

2. Placer le dossier dans `C:\xampp\htdocs\horizons-lointains`

3. Installer les dépendances PHP :
cd C:\xampp\htdocs\horizons-lointains
composer install

## Base de données

1. Lancer XAMPP (Apache + MySQL)
2. Ouvrir phpMyAdmin : `http://localhost:8080/phpmyadmin`
3. Créer une base de données `horizons_lointains`
4. Importer le fichier `database/database.sql` (onglet Importer)

## Configuration

Créer un fichier `config.php` à la racine du projet avec :

```php
<?php
define('BASE_URL', '/horizons-lointains/');
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'horizons_lointains');
define('DB_USER', 'root');
define('DB_PASSWORD', '');

define('MONGODB_URI', 'votre_lien_mongodb_atlas');
define('MAIL_USERNAME', 'votre_email@gmail.com');
define('MAIL_PASSWORD', 'votre_mot_de_passe_application');
```

Ce fichier est dans le `.gitignore` et ne doit jamais être commité.

## Lancer le projet

1. Démarrer Apache et MySQL dans XAMPP
2. Ouvrir le navigateur : `http://localhost:8080/horizons-lointains/`

## Synchronisation MongoDB

Pour synchroniser les statistiques vers MongoDB Atlas :
`http://localhost:8080/horizons-lointains/api/sync-mongodb.php`

## Identifiants de test

| Rôle           | Email                         | Mot de passe |
|--------------- |------------------------------ |              |
| Administrateur | admin@horizons-lointains.fr   | password     |
| Employé        | employe@horizons-lointains.fr | password     |

## Technologies

- **Front-end** : HTML5, CSS3, Bootstrap 5, JavaScript (AJAX/fetch)
- **Back-end** : PHP 8.2 (POO), PDO, PHPMailer
- **Base de données** : MySQL (relationnelle), MongoDB Atlas (non relationnelle)
- **Outils** : Git/GitHub, Figma, Composer

## Lancer avec Docker
1. Installer Docker Desktop
2. Lancer : `docker-compose up --build`
3. Ouvrir le navigateur : `http://localhost:8081/` (Horizons Lointains) ou `http://localhost:8082/` (Le Pacte de Gray)

- **Déploiement** : Local (XAMPP)

