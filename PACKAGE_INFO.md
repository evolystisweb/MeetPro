# Package de Deploiement MeetSync Evolystis

## Informations du Package

**Fichier ZIP :** `meetsync-evolystis-deploy.zip`
**Taille :** 261 KB
**Date de creation :** 28 Decembre 2024
**Version :** 1.0.0 Evolystis Edition
**Statut :** Production Ready

---

## Que Contient ce Package ?

### 1. Application Frontend (dist/)
- Page d'accueil avec elements 3D
- Systeme d'authentification
- Dashboard utilisateur
- Panel administration
- Gestion des evenements et reservations
- Design responsive et moderne

### 2. Backend API (api/)
- **Authentification :**
  - Login : `api/auth/login.php`
  - Inscription : `api/auth/register.php`

- **Administration :**
  - Statistiques : `api/admin/stats.php`
  - Utilisateurs : `api/admin/users.php`
  - Plans : `api/admin/plans.php`
  - Abonnements : `api/admin/subscriptions.php`

- **Configuration :**
  - Base de donnees : `api/config/database.php` ✅ DEJA CONFIGURE
  - CORS : `api/config/cors.php`

- **Installation :**
  - Script automatique : `api/install/setup.php` ✅ PRE-REMPLI

### 3. Base de Donnees (database/)
- Schema MySQL complet : `mysql_schema.sql`
- Toutes les tables necessaires
- Donnees de test incluses

### 4. Documentation
- `README_DEPLOY.md` - Guide de deploiement complet
- `CONFIGURATION_EVOLYSTIS.md` - Configuration detaillee
- `INTEGRATION_GUIDE.md` - Guide d'integration
- `README.md` - Documentation generale

---

## Configuration Pre-Configuree

### Base de Donnees Evolystis

```
Host     : www.evomeetsync.evolystis.com
Database : evolysti_evomeetsync
Username : evolysti_evomeetsync
Password : Evolystis@2025@2025
```

✅ **Deja configure dans :**
- `api/config/database.php` (connexion API)
- `api/install/setup.php` (script installation)

### Comptes Pre-Configures

**Super Admin :**
```
Email    : evolystisweb@gmail.com
Password : Soufian@2025
Role     : admin
```

**Utilisateur Test :**
```
Email    : test@meetsync.com
Password : Test@2025
Role     : user
Plan     : Gratuit
```

---

## Deploiement en 5 Etapes

### 1. Upload du ZIP
Uploader `meetsync-evolystis-deploy.zip` sur votre serveur via FTP/SFTP

### 2. Extraction
```bash
unzip meetsync-evolystis-deploy.zip
```

### 3. Placement des Fichiers
```bash
# Copier le contenu de dist/ a la racine
cp -r dist/* /public_html/

# Copier l'API
cp -r api /public_html/

# Optionnel : copier database/
cp -r database /public_html/
```

### 4. Installation Base de Donnees
Acceder a : `https://www.evomeetsync.evolystis.com/api/install/setup.php`

Le formulaire est pre-rempli, cliquer sur "Installer"

### 5. Securisation
```bash
# Supprimer le dossier d'installation
rm -rf /public_html/api/install/
```

---

## URLs Importantes

**Frontend :**
- Accueil : `https://www.evomeetsync.evolystis.com/`
- Connexion : `https://www.evomeetsync.evolystis.com/auth`
- Dashboard : `https://www.evomeetsync.evolystis.com/dashboard`
- Admin : `https://www.evomeetsync.evolystis.com/admin`

**Backend API :**
- Installation : `https://www.evomeetsync.evolystis.com/api/install/setup.php`
- Login : `https://www.evomeetsync.evolystis.com/api/auth/login.php`
- Register : `https://www.evomeetsync.evolystis.com/api/auth/register.php`

---

## Caracteristiques Principales

### Interface Utilisateur
- Design moderne avec Tailwind CSS et shadcn/ui
- Elements 3D sur la page d'accueil
- Theme clair/sombre
- Responsive (mobile, tablette, desktop)
- Animations fluides

### Fonctionnalites
- Systeme de reservation d'evenements
- Gestion de disponibilites
- Dashboard utilisateur personnalise
- Panel administration complet
- Systeme d'abonnements (Gratuit, Pro, Business)
- Statistiques en temps reel

### Securite
- Authentification JWT
- Hachage bcrypt pour les mots de passe
- Protection CSRF
- Configuration CORS securisee
- Validation des entrees
- Protection SQL injection

### Plans Tarifaires
- **Gratuit :** 5 evenements/mois, 1 type d'evenement
- **Pro (79 MAD/mois) :** Evenements illimites, 10 types
- **Business (149 MAD/mois) :** Tout illimite + support prioritaire

---

## Support et Maintenance

### Prerequis Serveur
- PHP 7.4+
- MySQL 5.7+
- Apache avec mod_rewrite
- Extensions PHP : PDO, PDO_MySQL, JSON

### Backups Recommandes
- Base de donnees : Quotidien
- Fichiers : Hebdomadaire
- Logs : Conservation 30 jours

### Monitoring
- Surveiller les logs Apache/PHP
- Verifier les tentatives de connexion
- Monitorer l'utilisation disque et memoire

---

## Checklist Post-Deploiement

- [ ] Fichiers uploades et extraits
- [ ] Base de donnees installee
- [ ] Test page d'accueil fonctionnel
- [ ] Test connexion admin OK
- [ ] Dossier api/install/ supprime
- [ ] Mot de passe admin change
- [ ] HTTPS active
- [ ] Certificat SSL installe
- [ ] Backups configures
- [ ] Monitoring en place

---

## Modifications Evolystis Incluses

### 1. Branding
- Footer avec signature "Developpe par Evolystis"
- Lien vers www.evolystis.com
- Favicons personnalises

### 2. Configuration
- Identifiants base de donnees Evolystis pre-configures
- Script d'installation pre-rempli
- API completement configuree

### 3. Documentation
- Guide de deploiement complet
- Configuration detaillee
- Troubleshooting inclus

---

## Contacts

**Projet :** MeetSync by Evolystis
**Website :** https://www.evolystis.com
**Domaine :** www.evomeetsync.evolystis.com

---

## Notes Importantes

1. **Securite :** Changez le mot de passe admin apres la premiere connexion
2. **Installation :** Supprimez api/install/ apres l'installation
3. **HTTPS :** Activez SSL/TLS pour la production
4. **Backups :** Configurez des sauvegardes automatiques
5. **Monitoring :** Surveillez les logs regulierement

---

## Prochaines Etapes

1. ✅ Telecharger `meetsync-evolystis-deploy.zip`
2. ✅ Lire `README_DEPLOY.md` pour les instructions detaillees
3. ✅ Uploader sur le serveur Evolystis
4. ✅ Executer l'installation
5. ✅ Tester les fonctionnalites
6. ✅ Securiser l'installation
7. ✅ Commencer a utiliser !

---

**Date :** 28 Decembre 2024
**Version :** 1.0.0 Evolystis Edition
**Statut :** Production Ready

**Le package est pret pour le deploiement sur votre serveur Evolystis !**
