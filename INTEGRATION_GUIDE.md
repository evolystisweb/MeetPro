# Guide d'Intégration PHP/MySQL - MeetSync

## 📋 Table des Matières

1. [Prérequis](#prérequis)
2. [Installation de la Base de Données](#installation-de-la-base-de-données)
3. [Configuration du Serveur PHP](#configuration-du-serveur-php)
4. [Installation des Dépendances](#installation-des-dépendances)
5. [Configuration de l'API](#configuration-de-lapi)
6. [Intégration Frontend](#intégration-frontend)
7. [Tests et Déploiement](#tests-et-déploiement)
8. [Maintenance et Monitoring](#maintenance-et-monitoring)

---

## 🔧 Prérequis

### Serveur Requis

- **PHP** : Version 7.4 ou supérieure (recommandé : PHP 8.1+)
- **MySQL** : Version 5.7 ou supérieure (recommandé : MySQL 8.0+)
- **Apache** : Version 2.4+ avec mod_rewrite activé
- **Composer** : Gestionnaire de dépendances PHP

### Extensions PHP Requises

```bash
php -m | grep -E 'pdo|pdo_mysql|mbstring|json|openssl'
```

Extensions nécessaires :
- `pdo`
- `pdo_mysql`
- `mbstring`
- `json`
- `openssl`
- `curl`

---

## 💾 Installation de la Base de Données

### Étape 1 : Créer la Base de Données

```bash
# Se connecter à MySQL
mysql -u root -p

# Ou depuis phpMyAdmin
```

### Étape 2 : Importer le Schéma

```bash
# Via ligne de commande
mysql -u root -p < database/mysql_schema.sql

# Ou copier-coller le contenu dans phpMyAdmin
```

Le script va créer :
- ✅ Base de données `meetsync`
- ✅ 14 tables avec relations
- ✅ Index optimisés
- ✅ Triggers automatiques
- ✅ Vues pour statistiques
- ✅ Procédures stockées
- ✅ Plans tarifaires par défaut

### Étape 3 : Vérifier l'Installation

```sql
-- Vérifier les tables
SHOW TABLES FROM meetsync;

-- Vérifier les plans par défaut
SELECT * FROM meetsync.subscription_plans;
```

---

## ⚙️ Configuration du Serveur PHP

### Configuration Apache

1. **Activer mod_rewrite**

```bash
# Sur Ubuntu/Debian
sudo a2enmod rewrite
sudo systemctl restart apache2

# Sur CentOS/RHEL
# Modifier /etc/httpd/conf/httpd.conf
# AllowOverride All
```

2. **Configuration VirtualHost**

Créer `/etc/apache2/sites-available/meetsync.conf` :

```apache
<VirtualHost *:80>
    ServerName meetsync.local
    ServerAdmin admin@meetsync.com
    DocumentRoot /var/www/meetsync

    # Frontend React (build)
    <Directory /var/www/meetsync>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted

        # Redirection SPA
        RewriteEngine On
        RewriteBase /
        RewriteRule ^index\.html$ - [L]
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule . /index.html [L]
    </Directory>

    # API PHP
    Alias /api /var/www/meetsync/api
    <Directory /var/www/meetsync/api>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/meetsync_error.log
    CustomLog ${APACHE_LOG_DIR}/meetsync_access.log combined
</VirtualHost>
```

3. **Activer le site**

```bash
sudo a2ensite meetsync
sudo systemctl reload apache2
```

### Configuration Nginx (Alternative)

```nginx
server {
    listen 80;
    server_name meetsync.local;
    root /var/www/meetsync;
    index index.html;

    # Frontend
    location / {
        try_files $uri $uri/ /index.html;
    }

    # API PHP
    location /api {
        alias /var/www/meetsync/api;
        try_files $uri $uri/ /api/index.php?$query_string;

        location ~ \.php$ {
            include fastcgi_params;
            fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
            fastcgi_param SCRIPT_FILENAME $request_filename;
        }
    }

    # Logs
    access_log /var/log/nginx/meetsync_access.log;
    error_log /var/log/nginx/meetsync_error.log;
}
```

---

## 📦 Installation des Dépendances

### Backend PHP

```bash
cd api/
composer install
```

Cela va installer :
- `firebase/php-jwt` - Gestion des tokens JWT

### Frontend React

```bash
npm install
npm run build
```

---

## 🔐 Configuration de l'API

### 1. Configuration Base de Données

Éditer `api/config/database.php` :

```php
private $host = 'localhost';        // Votre hôte MySQL
private $db_name = 'meetsync';      // Nom de la base
private $username = 'votre_user';   // Utilisateur MySQL
private $password = 'votre_pass';   // Mot de passe MySQL
```

### 2. Configuration JWT

Éditer `api/utils/auth.php` :

```php
private static $secret_key = "GENERER_UNE_CLE_TRES_LONGUE_ET_ALEATOIRE_ICI";
```

**Générer une clé sécurisée :**

```bash
# Linux/Mac
openssl rand -base64 64

# Ou en PHP
php -r "echo bin2hex(random_bytes(64));"
```

### 3. Variables d'Environnement Frontend

Créer/modifier `.env` à la racine du projet :

```env
VITE_API_URL=http://votre-domaine.com/api
```

### 4. Permissions Fichiers

```bash
# Rendre les fichiers accessibles par Apache
sudo chown -R www-data:www-data /var/www/meetsync
sudo chmod -R 755 /var/www/meetsync

# Logs
sudo mkdir -p /var/www/meetsync/api/logs
sudo chown -R www-data:www-data /var/www/meetsync/api/logs
sudo chmod -R 775 /var/www/meetsync/api/logs
```

---

## 🔗 Intégration Frontend

### Modifier les URLs d'API

Dans tous les composants React, remplacer les appels Supabase par des appels à votre API PHP.

**Exemple - Hook d'authentification :**

```typescript
// src/hooks/useAuth.tsx

const signIn = async (email: string, password: string) => {
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/auth/login.php`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password })
    });

    const data = await response.json();

    if (data.success) {
      localStorage.setItem('auth_token', data.token);
      setUser(data.user);
      toast({ title: 'Connexion réussie' });
    } else {
      toast({ title: 'Erreur', description: data.message });
    }
  } catch (error) {
    toast({ title: 'Erreur', description: 'Erreur de connexion' });
  }
};
```

**Exemple - Hook Admin :**

```typescript
// src/hooks/useAdminData.tsx

const fetchAdminData = async () => {
  const token = localStorage.getItem('auth_token');

  const response = await fetch(`${import.meta.env.VITE_API_URL}/admin/stats.php`, {
    headers: {
      'Authorization': `Bearer ${token}`
    }
  });

  const data = await response.json();
  setStats(data.stats);
};
```

---

## 🧪 Tests et Déploiement

### Tests de l'API

**1. Test de Connexion BDD**

```bash
php -r "
\$conn = new PDO('mysql:host=localhost;dbname=meetsync', 'root', 'password');
echo 'Connexion OK\n';
"
```

**2. Test des Endpoints**

```bash
# Inscription
curl -X POST http://localhost/api/auth/register.php \
  -H "Content-Type: application/json" \
  -d '{"email":"test@test.com","password":"password123"}'

# Connexion
curl -X POST http://localhost/api/auth/login.php \
  -H "Content-Type: application/json" \
  -d '{"email":"test@test.com","password":"password123"}'

# Stats Admin (avec token)
curl -X GET http://localhost/api/admin/stats.php \
  -H "Authorization: Bearer VOTRE_TOKEN"
```

### Build Frontend

```bash
npm run build

# Les fichiers statiques sont dans dist/
# Copier vers le serveur web
sudo cp -r dist/* /var/www/meetsync/
```

### Déploiement Production

**Checklist :**

- [ ] Base de données créée et sécurisée
- [ ] Utilisateur MySQL avec privilèges limités
- [ ] Clé JWT sécurisée et unique
- [ ] `display_errors = Off` dans php.ini
- [ ] HTTPS activé (Let's Encrypt)
- [ ] Firewall configuré
- [ ] Backups automatiques configurés
- [ ] Logs activés et monitored

---

## 🔒 Sécurité

### 1. Utilisateur MySQL Dédié

```sql
-- Créer un utilisateur dédié
CREATE USER 'meetsync_user'@'localhost' IDENTIFIED BY 'mot_de_passe_fort';

-- Donner les permissions minimales
GRANT SELECT, INSERT, UPDATE, DELETE ON meetsync.* TO 'meetsync_user'@'localhost';

FLUSH PRIVILEGES;
```

### 2. Protection des Fichiers Sensibles

Créer `.htaccess` dans `/api/config/` :

```apache
# Bloquer l'accès aux fichiers de config
<Files "*">
    Require all denied
</Files>
```

### 3. Protection XSS et Injection SQL

✅ Déjà implémenté dans l'API :
- Requêtes préparées (PDO)
- Validation des entrées
- Headers de sécurité

---

## 📊 Maintenance et Monitoring

### Logs

```bash
# Logs Apache
tail -f /var/log/apache2/meetsync_error.log

# Logs PHP (à configurer dans php.ini)
error_log = /var/www/meetsync/api/logs/php_errors.log

# Logs MySQL
sudo tail -f /var/log/mysql/error.log
```

### Backups Automatiques

**Script de backup MySQL :**

```bash
#!/bin/bash
# /usr/local/bin/backup_meetsync.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/meetsync"
mkdir -p $BACKUP_DIR

mysqldump -u root -p'password' meetsync | gzip > $BACKUP_DIR/meetsync_$DATE.sql.gz

# Garder seulement 30 derniers jours
find $BACKUP_DIR -type f -mtime +30 -delete
```

**Crontab (daily 2AM) :**

```bash
0 2 * * * /usr/local/bin/backup_meetsync.sh
```

### Monitoring Performance

```sql
-- Requêtes lentes
SHOW VARIABLES LIKE 'slow_query_log';
SET GLOBAL slow_query_log = 'ON';
SET GLOBAL long_query_time = 2;

-- Analyser les performances
EXPLAIN SELECT * FROM bookings WHERE scheduled_at > NOW();
```

---

## 📚 Documentation API

### Endpoints Disponibles

#### **Authentification**

| Méthode | Endpoint | Auth | Description |
|---------|----------|------|-------------|
| POST | `/auth/register.php` | Non | Inscription |
| POST | `/auth/login.php` | Non | Connexion |

#### **Admin**

| Méthode | Endpoint | Auth | Description |
|---------|----------|------|-------------|
| GET | `/admin/stats.php` | Admin | Statistiques |
| GET | `/admin/users.php` | Admin | Liste utilisateurs |
| PUT | `/admin/users.php?id=` | Admin | Modifier utilisateur |
| GET | `/admin/plans.php` | Admin | Liste plans |
| POST | `/admin/plans.php` | Admin | Créer plan |
| PUT | `/admin/plans.php?id=` | Admin | Modifier plan |
| GET | `/admin/subscriptions.php` | Admin | Liste abonnements |
| PUT | `/admin/subscriptions.php?id=` | Admin | Modifier abonnement |

---

## 🆘 Dépannage

### Erreur : "Connection refused"

```bash
# Vérifier que MySQL est démarré
sudo systemctl status mysql

# Vérifier que PHP-FPM est démarré
sudo systemctl status php8.1-fpm
```

### Erreur : "Access denied"

Vérifier les credentials dans `api/config/database.php`

### Erreur 500

```bash
# Activer temporairement les erreurs PHP
# Dans php.ini ou .htaccess
php_flag display_errors on
```

### CORS Errors

Vérifier que `api/config/cors.php` est bien inclus dans tous les endpoints.

---

## 📞 Support

Pour toute question :
- 📧 Email : support@meetsync.com
- 📖 Documentation : https://docs.meetsync.com
- 🐛 Issues : GitHub Issues

---

## 📝 Licence

© 2024 MeetSync - Tous droits réservés
