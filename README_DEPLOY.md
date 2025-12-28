# Guide de Deploiement - MeetSync Evolystis

## Informations du Package

**Fichier :** `meetsync-evolystis-deploy.zip`
**Taille :** 258 KB
**Date :** 28 Decembre 2024
**Version :** 1.0.0 Evolystis Edition

---

## Contenu du ZIP

```
meetsync-evolystis-deploy.zip
├── dist/                          # Application buildee (frontend)
│   ├── index.html                # Page principale
│   ├── assets/                   # CSS et JavaScript compiles
│   ├── favicon.ico              # Icone du site
│   └── robots.txt               # Configuration SEO
│
├── api/                          # Backend PHP
│   ├── auth/                    # Authentification
│   │   ├── login.php           # Connexion
│   │   └── register.php        # Inscription
│   ├── admin/                   # Administration
│   │   ├── stats.php           # Statistiques
│   │   ├── users.php           # Gestion utilisateurs
│   │   ├── plans.php           # Gestion plans
│   │   └── subscriptions.php   # Gestion abonnements
│   ├── config/                  # Configuration
│   │   ├── database.php        # Config BDD (DEJA CONFIGURE)
│   │   └── cors.php            # Configuration CORS
│   ├── utils/                   # Utilitaires
│   │   └── auth.php            # Fonctions auth
│   ├── install/                 # Installation
│   │   └── setup.php           # Script installation BDD
│   ├── .htaccess               # Configuration Apache
│   └── composer.json           # Dependencies PHP
│
├── database/                     # Schema de base de donnees
│   └── mysql_schema.sql        # Schema MySQL complet
│
└── Documentation/
    ├── CONFIGURATION_EVOLYSTIS.md  # Configuration complete
    ├── INTEGRATION_GUIDE.md        # Guide integration
    └── README.md                   # Documentation principale
```

---

## Etapes de Deploiement

### 1. Preparation du Serveur

**Verifier les prerequis sur le serveur :**
```
- PHP 7.4 ou superieur
- MySQL 5.7 ou superieur
- Apache avec mod_rewrite active
- Extension PHP : PDO, PDO_MySQL, JSON
```

### 2. Upload des Fichiers

**A. Se connecter au serveur via FTP/SFTP**
- Host : www.evomeetsync.evolystis.com
- Utiliser vos identifiants FTP

**B. Uploader le contenu du ZIP**

**Option 1 : Upload du ZIP puis extraction sur le serveur**
```bash
# Uploader meetsync-evolystis-deploy.zip
# Puis sur le serveur :
unzip meetsync-evolystis-deploy.zip
```

**Option 2 : Extraction locale puis upload**
```bash
# Sur votre ordinateur :
unzip meetsync-evolystis-deploy.zip
# Puis uploader tous les dossiers via FTP
```

**C. Structure finale sur le serveur**

Le contenu doit etre place dans le dossier racine (public_html ou www) :

```
/public_html/  (ou /www/ ou /htdocs/)
├── index.html                 # Depuis dist/
├── assets/                    # Depuis dist/assets/
├── favicon.ico               # Depuis dist/
├── robots.txt                # Depuis dist/
├── api/                      # Dossier api complet
└── database/                 # Dossier database (optionnel)
```

**IMPORTANT :** Les fichiers du dossier `dist/` doivent etre places a la RACINE du serveur web, pas dans un sous-dossier.

### 3. Configuration de la Base de Donnees

**La configuration est DEJA faite dans `api/config/database.php` :**

```php
Host     : www.evomeetsync.evolystis.com
Database : evolysti_evomeetsync
Username : evolysti_evomeetsync
Password : Evolystis@2025@2025
```

**Deux options pour creer la base :**

#### Option A : Installation Automatique (RECOMMANDEE)

1. Acceder a : `https://www.evomeetsync.evolystis.com/api/install/setup.php`
2. Le formulaire est pre-rempli avec vos identifiants
3. Cliquer sur "Installer la base de donnees"
4. Attendre la confirmation de succes
5. Supprimer le dossier `api/install/` par securite

#### Option B : Installation Manuelle via phpMyAdmin

1. Se connecter a phpMyAdmin
2. Selectionner la base `evolysti_evomeetsync`
3. Aller dans l'onglet "SQL"
4. Importer le fichier `database/mysql_schema.sql`
5. Executer le script

### 4. Configuration Apache

**Le fichier `api/.htaccess` est inclus et configure :**
- Redirection des requetes vers les bons endpoints
- Configuration CORS
- Protection des fichiers sensibles

**Verifier que mod_rewrite est active :**
```bash
# Sur le serveur
a2enmod rewrite
service apache2 restart
```

### 5. Verification du Deploiement

**Tester les URLs suivantes :**

1. **Page d'accueil :**
   ```
   https://www.evomeetsync.evolystis.com/
   ```
   ✅ Doit afficher la page d'accueil MeetSync

2. **Page d'authentification :**
   ```
   https://www.evomeetsync.evolystis.com/auth
   ```
   ✅ Doit afficher le formulaire de connexion

3. **Test API :**
   ```
   https://www.evomeetsync.evolystis.com/api/auth/login.php
   ```
   ✅ Doit retourner une erreur JSON (405 Method Not Allowed si GET)

### 6. Premiere Connexion

**Compte Super Admin (cree lors de l'installation) :**
```
URL      : https://www.evomeetsync.evolystis.com/auth
Email    : evolystisweb@gmail.com
Password : Soufian@2025
```

**Apres connexion :**
1. Acceder au dashboard : `/dashboard`
2. Acceder a l'admin : `/admin`
3. Verifier les statistiques
4. Changer le mot de passe admin

---

## Securite Post-Deploiement

### Actions Immediates

1. **Supprimer le dossier d'installation :**
   ```bash
   rm -rf /public_html/api/install/
   ```

2. **Verifier les permissions :**
   ```bash
   # Fichiers : 644
   find /public_html -type f -exec chmod 644 {} \;

   # Dossiers : 755
   find /public_html -type d -exec chmod 755 {} \;
   ```

3. **Changer le mot de passe admin :**
   - Se connecter avec evolystisweb@gmail.com
   - Aller dans Parametres > Securite
   - Modifier le mot de passe

### Protection Renforcee

1. **Activer HTTPS :**
   - Installer un certificat SSL (Let's Encrypt gratuit)
   - Forcer HTTPS dans .htaccess

2. **Configurer les backups :**
   - Backup quotidien de la base de donnees
   - Backup hebdomadaire des fichiers

3. **Monitoring :**
   - Surveiller les logs d'erreur PHP
   - Verifier regulierement les tentatives de connexion

---

## Depannage

### Probleme : Page blanche

**Solution :**
```bash
# Verifier les logs d'erreur
tail -f /var/log/apache2/error.log

# Verifier les permissions
ls -la /public_html/
```

### Probleme : Erreur de connexion base de donnees

**Solutions :**
1. Verifier les identifiants dans `api/config/database.php`
2. Tester la connexion MySQL :
   ```bash
   mysql -h www.evomeetsync.evolystis.com -u evolysti_evomeetsync -p
   ```
3. Verifier que l'IP du serveur web est autorisee dans MySQL

### Probleme : API ne repond pas

**Solutions :**
1. Verifier que mod_rewrite est actif
2. Verifier le fichier `api/.htaccess`
3. Tester directement :
   ```
   https://www.evomeetsync.evolystis.com/api/config/cors.php
   ```

### Probleme : 404 sur les routes

**Solution :**
```apache
# Ajouter dans le .htaccess racine
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteBase /
  RewriteRule ^index\.html$ - [L]
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule . /index.html [L]
</IfModule>
```

---

## Support Technique

### Contacts

**Projet :** MeetSync by Evolystis
**Domaine :** www.evomeetsync.evolystis.com
**Support :** www.evolystis.com

### Documentation Complete

Consulter les fichiers suivants pour plus d'informations :
- `CONFIGURATION_EVOLYSTIS.md` - Configuration detaillee
- `INTEGRATION_GUIDE.md` - Guide d'integration
- `README.md` - Documentation principale

---

## Checklist de Deploiement

- [ ] Serveur prepare (PHP, MySQL, Apache)
- [ ] Fichiers uploades depuis le ZIP
- [ ] Structure des dossiers correcte
- [ ] Base de donnees creee et configuree
- [ ] Installation via setup.php executee
- [ ] Dossier api/install/ supprime
- [ ] Permissions des fichiers configurees
- [ ] HTTPS active
- [ ] Test page d'accueil OK
- [ ] Test connexion admin OK
- [ ] Mot de passe admin change
- [ ] Backups configures

---

## Resume Rapide

```bash
# 1. Upload
unzip meetsync-evolystis-deploy.zip

# 2. Copier dist/ vers racine
cp -r dist/* /public_html/
cp -r api /public_html/
cp -r database /public_html/

# 3. Installation BDD
# Acceder a : https://www.evomeetsync.evolystis.com/api/install/setup.php

# 4. Securite
rm -rf /public_html/api/install/
chmod 644 /public_html/api/config/database.php

# 5. Test
# https://www.evomeetsync.evolystis.com/
# Login : evolystisweb@gmail.com / Soufian@2025
```

---

**Date de creation :** 28 Decembre 2024
**Version :** 1.0.0
**Statut :** Production Ready
