# 📅 MeetSync - Plateforme Professionnelle de Planification de Rendez-vous

## 🎯 Vue d'Ensemble

MeetSync est une solution complète de gestion de rendez-vous similaire à Calendly, conçue pour les professionnels et entreprises qui souhaitent optimiser la planification de leurs réunions et événements.

### ✨ Fonctionnalités Principales

#### 🔐 Authentification & Gestion Utilisateurs
- Inscription et connexion sécurisées
- Profils utilisateurs complets
- Système de rôles (Utilisateur / Admin)
- Gestion des sessions avec JWT

#### 📆 Gestion d'Événements Avancée
- Création de types d'événements personnalisables
- Durée configurable (15, 30, 60 minutes, etc.)
- Prix par événement (gratuit ou payant)
- Types de lieu : Vidéo, Physique, Téléphone
- Tampons temporels avant/après les rendez-vous
- Limites de réservation par jour
- Politiques de reprogrammation et annulation

#### ⏰ Gestion du Temps Professionnelle
- Configuration des disponibilités par jour de la semaine
- Créneaux horaires multiples par jour
- Gestion des fuseaux horaires
- Préavis minimum pour réservations
- Réservation anticipée maximum
- Calendrier visuel interactif

#### 💳 Système d'Abonnements
- **Plan Gratuit** : 3 événements, 50 réservations/mois
- **Plan Pro** : 20 événements, 500 réservations/mois - 29.99€/mois
- **Plan Business** : Illimité - 79.99€/mois
- Gestion automatique des limites
- Système de paiement intégrable

#### 🛠️ Dashboard Admin Complet
- Vue d'ensemble des statistiques
- Gestion des utilisateurs (activation/désactivation)
- Gestion des rôles et permissions
- Configuration des plans tarifaires
- Gestion des abonnements
- Suivi des revenus
- Statistiques en temps réel

#### 📊 Statistiques & Analyses
- Nombre d'utilisateurs actifs
- Total des réservations
- Revenus générés
- Taux de conversion
- Événements les plus populaires

## 🏗️ Architecture Technique

### Frontend
- **Framework** : React 18 + TypeScript
- **Build Tool** : Vite
- **UI Library** : shadcn/ui (Radix UI + Tailwind CSS)
- **State Management** : React Context + Hooks
- **Routing** : React Router v6
- **Forms** : React Hook Form + Zod
- **Date Handling** : date-fns

### Backend (PHP/MySQL)
- **Langage** : PHP 7.4+
- **Base de données** : MySQL 8.0+
- **Authentication** : JWT (JSON Web Tokens)
- **API** : REST API
- **Architecture** : MVC Pattern

### Base de Données
- 14 tables normalisées
- Relations avec contraintes d'intégrité
- Index optimisés pour les performances
- Triggers automatiques
- Vues pour statistiques
- Procédures stockées

## 📁 Structure du Projet

```
meetsync/
├── src/                          # Code source React
│   ├── components/              # Composants React
│   │   ├── admin/              # Composants admin
│   │   │   ├── AdminStats.tsx
│   │   │   ├── UsersManagement.tsx
│   │   │   ├── PlansManagement.tsx
│   │   │   └── SubscriptionsManagement.tsx
│   │   ├── ui/                 # Composants UI (shadcn)
│   │   ├── AvailabilitySettings.tsx
│   │   ├── AdvancedEventSettings.tsx
│   │   ├── BookingCalendar.tsx
│   │   ├── CreateEventDialog.tsx
│   │   └── ...
│   ├── pages/                  # Pages de l'application
│   │   ├── Index.tsx           # Page d'accueil
│   │   ├── Dashboard.tsx       # Dashboard utilisateur
│   │   ├── Admin.tsx           # Dashboard admin
│   │   ├── Auth.tsx            # Authentification
│   │   ├── BookingPage.tsx     # Page de réservation
│   │   └── ...
│   ├── hooks/                  # Custom React Hooks
│   │   ├── useAuth.tsx
│   │   ├── useEvents.tsx
│   │   ├── useAdminData.tsx
│   │   └── ...
│   └── integrations/           # Intégrations
│       └── supabase/
│
├── api/                         # Backend PHP
│   ├── config/                 # Configuration
│   │   ├── database.php
│   │   └── cors.php
│   ├── utils/                  # Utilitaires
│   │   └── auth.php           # JWT & authentification
│   ├── auth/                   # Endpoints authentification
│   │   ├── login.php
│   │   └── register.php
│   ├── admin/                  # Endpoints admin
│   │   ├── stats.php
│   │   ├── users.php
│   │   ├── plans.php
│   │   └── subscriptions.php
│   ├── composer.json
│   └── .htaccess
│
├── database/                    # Scripts SQL
│   └── mysql_schema.sql        # Schéma complet MySQL
│
├── public/                      # Fichiers statiques
│   ├── favicon.svg             # Icône calendrier
│   └── ...
│
├── INTEGRATION_GUIDE.md         # Guide d'intégration détaillé
├── README.md                    # Ce fichier
└── package.json
```

## 🚀 Installation et Configuration

### Prérequis

- Node.js 18+ et npm
- PHP 7.4+ (recommandé : PHP 8.1+)
- MySQL 8.0+
- Apache ou Nginx
- Composer

### Installation Rapide

1. **Cloner le projet**

```bash
git clone https://github.com/votre-repo/meetsync.git
cd meetsync
```

2. **Installer les dépendances Frontend**

```bash
npm install
```

3. **Installer les dépendances Backend**

```bash
cd api
composer install
```

4. **Configurer la base de données**

```bash
# Créer la base de données
mysql -u root -p < database/mysql_schema.sql
```

5. **Configuration**

Éditer `api/config/database.php` avec vos paramètres MySQL :

```php
private $host = 'localhost';
private $db_name = 'meetsync';
private $username = 'votre_user';
private $password = 'votre_password';
```

Générer une clé JWT dans `api/utils/auth.php` :

```bash
openssl rand -base64 64
```

6. **Build Frontend**

```bash
npm run build
```

7. **Déployer**

Copier les fichiers vers votre serveur web.

Pour des instructions détaillées, consultez [INTEGRATION_GUIDE.md](INTEGRATION_GUIDE.md)

## 🎨 Design & Interface

### Principe de Design

- **Interface moderne et épurée**
- **Responsive** : Mobile, Tablet, Desktop
- **Dark mode ready** (thème sombre disponible)
- **Accessibilité** : WCAG 2.1 Level AA
- **Animations fluides** avec Tailwind CSS
- **Palette de couleurs professionnelle**

### Composants UI

Utilisation de **shadcn/ui** pour une UI cohérente :
- Formulaires élégants
- Modales et dialogues
- Tables de données
- Cartes statistiques
- Calendrier interactif
- Boutons et badges

## 🔒 Sécurité

### Mesures Implémentées

✅ **Authentification**
- Mots de passe hashés (bcrypt)
- Tokens JWT sécurisés
- Sessions expirables

✅ **Base de Données**
- Requêtes préparées (PDO)
- Protection contre SQL Injection
- Validation des entrées

✅ **API**
- CORS configuré
- Rate limiting recommandé
- Headers de sécurité

✅ **Frontend**
- Protection XSS
- Validation côté client
- Routes protégées

## 📊 Base de Données

### Tables Principales

1. **users** - Utilisateurs
2. **user_profiles** - Profils étendus
3. **subscription_plans** - Plans tarifaires
4. **user_subscriptions** - Abonnements actifs
5. **events** - Types d'événements
6. **availability_schedules** - Disponibilités
7. **bookings** - Réservations
8. **payments** - Paiements
9. **notifications** - Notifications
10. **integrations** - Intégrations calendrier
11. **activity_logs** - Logs d'audit
12. **webhooks** - Webhooks

### Relations

```
users ──┬── user_profiles
        ├── user_subscriptions ── subscription_plans
        ├── events ── bookings
        ├── availability_schedules
        └── activity_logs
```

## 🛣️ Routes Frontend

| Route | Description | Auth |
|-------|-------------|------|
| `/` | Page d'accueil | Public |
| `/auth` | Connexion/Inscription | Public |
| `/dashboard` | Dashboard utilisateur | User |
| `/admin` | Dashboard admin | Admin |
| `/book/:eventId` | Page de réservation | Public |
| `/contact` | Contact | Public |
| `/help` | Aide | Public |
| `/documentation` | Documentation | Public |

## 🔌 API Endpoints

### Authentification

- `POST /api/auth/register.php` - Inscription
- `POST /api/auth/login.php` - Connexion

### Admin (Auth: Admin)

- `GET /api/admin/stats.php` - Statistiques
- `GET /api/admin/users.php` - Liste utilisateurs
- `PUT /api/admin/users.php?id=` - Modifier utilisateur
- `GET /api/admin/plans.php` - Plans tarifaires
- `POST /api/admin/plans.php` - Créer plan
- `PUT /api/admin/plans.php?id=` - Modifier plan
- `GET /api/admin/subscriptions.php` - Abonnements
- `PUT /api/admin/subscriptions.php?id=` - Modifier abonnement

## 🎯 Utilisation

### Pour les Utilisateurs

1. **Inscription** : Créer un compte gratuitement
2. **Créer un événement** : Définir type, durée, prix
3. **Configurer disponibilités** : Horaires de travail
4. **Partager le lien** : Recevoir des réservations
5. **Gérer les réservations** : Confirmer, reprogrammer, annuler

### Pour les Admins

1. **Accéder au dashboard admin** : `/admin`
2. **Voir les statistiques** : Utilisateurs, revenus, activité
3. **Gérer les utilisateurs** : Activer/désactiver, changer rôles
4. **Configurer les plans** : Prix, limites, fonctionnalités
5. **Suivre les abonnements** : Statuts, renouvellements

## 🚧 Fonctionnalités Futures

- [ ] Intégrations calendrier (Google, Outlook)
- [ ] Paiements Stripe/PayPal
- [ ] Notifications SMS
- [ ] API webhooks
- [ ] Export de données
- [ ] Rapports avancés
- [ ] Application mobile
- [ ] Multi-langue
- [ ] White label

## 📝 Développement

### Commandes Disponibles

```bash
npm run dev          # Serveur de développement
npm run build        # Build production
npm run preview      # Prévisualiser le build
npm run lint         # Linter ESLint
```

### Standards de Code

- **TypeScript** pour le typage strict
- **ESLint** pour la qualité du code
- **Prettier** pour le formatage
- **Convention** : Functional Components + Hooks

## 🤝 Contribution

Les contributions sont les bienvenues !

1. Fork le projet
2. Créer une branche (`git checkout -b feature/AmazingFeature`)
3. Commit (`git commit -m 'Add AmazingFeature'`)
4. Push (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📄 Licence

© 2024 MeetSync - Tous droits réservés

## 👨‍💻 Auteur

Développé avec ❤️ pour les professionnels de la gestion du temps

## 📞 Support

- 📧 Email : support@meetsync.com
- 📖 Documentation : [INTEGRATION_GUIDE.md](INTEGRATION_GUIDE.md)
- 🐛 Issues : GitHub Issues

## 🙏 Remerciements

- React & Vite
- shadcn/ui & Radix UI
- Tailwind CSS
- La communauté open source

---

**MeetSync** - Simplifiez votre gestion du temps 🚀
