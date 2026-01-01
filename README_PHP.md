# MeetSync - Version PHP + MySQL

## Architecture

Application complète en PHP avec MySQL classique (pas de React, pas de Supabase).

## Structure

```
/
├── includes/           # Configuration et fichiers communs
│   ├── config.php     # Configuration DB et fonctions utilitaires
│   ├── header.php     # Header commun
│   └── footer.php     # Footer commun
├── api/               # API backend (conservé pour compatibilité)
├── database/          # Schéma MySQL
├── admin/             # Sous-pages du panel admin
├── index.php          # Page d'accueil
├── auth.php           # Connexion/Inscription
├── logout.php         # Déconnexion
├── dashboard.php      # Dashboard utilisateur
├── event-*.php        # Gestion des événements
├── booking.php        # Page de réservation publique
├── admin.php          # Panel administrateur
└── *.php              # Autres pages (contact, help, etc.)
```

## Installation

1. Uploader tous les fichiers sur votre hébergement PHP
2. Créer une base de données MySQL
3. Importer le fichier `database/mysql_schema.sql`
4. Configurer les credentials dans `includes/config.php`
5. Accéder à votre site

## Configuration Base de Données

Modifier dans `includes/config.php` :

```php
define('DB_HOST', 'votre_host');
define('DB_NAME', 'votre_database');
define('DB_USER', 'votre_username');
define('DB_PASS', 'votre_password');
```

## Compte Admin par Défaut

Email : `evolystisweb@gmail.com`
Password : `Soufian@2025`

## Fonctionnalités

### Utilisateur
- Inscription/Connexion
- Créer et gérer des événements
- Voir les réservations
- Partager des liens de réservation

### Public
- Réserver un créneau via lien public
- Pages informatives (contact, aide, documentation)

### Admin
- Statistiques globales
- Gestion des utilisateurs
- Vue d'ensemble des événements
- Gestion des plans d'abonnement

## Prérequis

- PHP 7.4+
- MySQL 5.7+
- Extension PDO MySQL
- mod_rewrite activé (Apache)

## Sécurité

- Toutes les requêtes SQL utilisent des prepared statements
- Mots de passe hashés avec bcrypt
- Protection CSRF via sessions PHP
- Validation des données côté serveur

## Support

Email : contact@meetsync.com
Documentation : /documentation.php
