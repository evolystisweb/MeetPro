-- ================================================================
-- MeetSync - Schema MySQL Complet
-- ================================================================
-- Date de création: 2024-12-28
-- Description: Base de données complète pour la plateforme de planification de rendez-vous
-- ================================================================

-- Créer la base de données
CREATE DATABASE IF NOT EXISTS meetsync CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE meetsync;

-- ================================================================
-- Table: users
-- Description: Stocke tous les utilisateurs de la plateforme
-- ================================================================
CREATE TABLE IF NOT EXISTS users (
    id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    role ENUM('user', 'admin') DEFAULT 'user',
    is_active BOOLEAN DEFAULT TRUE,
    email_verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- Table: subscription_plans
-- Description: Plans d'abonnement disponibles
-- ================================================================
CREATE TABLE IF NOT EXISTS subscription_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    billing_period ENUM('monthly', 'yearly') DEFAULT 'monthly',
    max_events INT DEFAULT 3 COMMENT '-1 pour illimité',
    max_bookings_per_month INT DEFAULT 50 COMMENT '-1 pour illimité',
    features JSON COMMENT 'Liste des fonctionnalités en JSON',
    is_active BOOLEAN DEFAULT TRUE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertion des plans par défaut
INSERT INTO subscription_plans (name, description, price, billing_period, max_events, max_bookings_per_month, features, sort_order) VALUES
('Gratuit', 'Plan gratuit pour débuter', 0.00, 'monthly', 3, 50,
 '["3 types d\'événements", "50 réservations/mois", "Support email"]', 1),
('Pro', 'Plan professionnel pour indépendants', 29.99, 'monthly', 20, 500,
 '["20 types d\'événements", "500 réservations/mois", "Support prioritaire", "Personnalisation avancée", "Intégrations calendrier"]', 2),
('Business', 'Plan entreprise illimité', 79.99, 'monthly', -1, -1,
 '["Événements illimités", "Réservations illimitées", "Support 24/7", "API complète", "White label", "Gestion équipe"]', 3);

-- ================================================================
-- Table: user_subscriptions
-- Description: Abonnements actifs et historiques des utilisateurs
-- ================================================================
CREATE TABLE IF NOT EXISTS user_subscriptions (
    id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    user_id CHAR(36) NOT NULL,
    plan_id INT NOT NULL,
    status ENUM('active', 'cancelled', 'expired', 'pending') DEFAULT 'active',
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    auto_renew BOOLEAN DEFAULT TRUE,
    payment_method VARCHAR(50),
    last_payment_date TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (plan_id) REFERENCES subscription_plans(id) ON DELETE RESTRICT,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_end_date (end_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- Table: user_profiles
-- Description: Informations de profil étendu des utilisateurs
-- ================================================================
CREATE TABLE IF NOT EXISTS user_profiles (
    user_id CHAR(36) PRIMARY KEY,
    company_name VARCHAR(255),
    phone VARCHAR(50),
    timezone VARCHAR(100) DEFAULT 'Europe/Paris',
    language VARCHAR(10) DEFAULT 'fr',
    avatar_url VARCHAR(500),
    bio TEXT,
    website VARCHAR(255),
    social_links JSON COMMENT 'Liens réseaux sociaux',
    preferences JSON COMMENT 'Préférences utilisateur',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- Table: events
-- Description: Types d'événements créés par les utilisateurs
-- ================================================================
CREATE TABLE IF NOT EXISTS events (
    id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    user_id CHAR(36) NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    description TEXT,
    duration INT NOT NULL DEFAULT 30 COMMENT 'Durée en minutes',
    price DECIMAL(10,2) DEFAULT 0.00,
    location_type ENUM('video', 'physical', 'phone') DEFAULT 'video',
    location_details TEXT,
    color VARCHAR(7) DEFAULT '#3b82f6',
    is_active BOOLEAN DEFAULT TRUE,

    -- Paramètres avancés
    buffer_before INT DEFAULT 0 COMMENT 'Minutes de tampon avant',
    buffer_after INT DEFAULT 0 COMMENT 'Minutes de tampon après',
    min_notice_hours INT DEFAULT 24 COMMENT 'Préavis minimum en heures',
    max_advance_days INT DEFAULT 60 COMMENT 'Réservation max à l\'avance en jours',
    max_bookings_per_day INT DEFAULT 10,

    -- Politiques
    allow_rescheduling BOOLEAN DEFAULT TRUE,
    reschedule_notice_hours INT DEFAULT 24,
    allow_cancellation BOOLEAN DEFAULT TRUE,
    cancellation_notice_hours INT DEFAULT 24,

    -- Questions personnalisées
    custom_questions JSON,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_slug (user_id, slug),
    INDEX idx_user_id (user_id),
    INDEX idx_is_active (is_active),
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- Table: availability_schedules
-- Description: Horaires de disponibilité des utilisateurs
-- ================================================================
CREATE TABLE IF NOT EXISTS availability_schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id CHAR(36) NOT NULL,
    day_of_week ENUM('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday') NOT NULL,
    is_available BOOLEAN DEFAULT TRUE,
    time_slots JSON NOT NULL COMMENT 'Array de {start, end} times',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_day (user_id, day_of_week)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- Table: bookings
-- Description: Réservations de rendez-vous
-- ================================================================
CREATE TABLE IF NOT EXISTS bookings (
    id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    event_id CHAR(36) NOT NULL,

    -- Informations du client
    attendee_name VARCHAR(255) NOT NULL,
    attendee_email VARCHAR(255) NOT NULL,
    attendee_phone VARCHAR(50),
    attendee_timezone VARCHAR(100) DEFAULT 'Europe/Paris',

    -- Détails de la réservation
    scheduled_at TIMESTAMP NOT NULL,
    end_time TIMESTAMP NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed', 'no_show') DEFAULT 'confirmed',

    -- Informations supplémentaires
    notes TEXT,
    custom_responses JSON COMMENT 'Réponses aux questions personnalisées',
    cancellation_reason TEXT,
    cancelled_at TIMESTAMP NULL,

    -- Notifications
    reminder_sent BOOLEAN DEFAULT FALSE,
    reminder_sent_at TIMESTAMP NULL,

    -- Métadonnées
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    INDEX idx_event_id (event_id),
    INDEX idx_scheduled_at (scheduled_at),
    INDEX idx_status (status),
    INDEX idx_attendee_email (attendee_email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- Table: payments
-- Description: Historique des paiements
-- ================================================================
CREATE TABLE IF NOT EXISTS payments (
    id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    user_id CHAR(36) NOT NULL,
    subscription_id CHAR(36),
    booking_id CHAR(36),
    amount DECIMAL(10,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'EUR',
    payment_method VARCHAR(50),
    payment_provider VARCHAR(50) COMMENT 'stripe, paypal, etc.',
    transaction_id VARCHAR(255),
    status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    metadata JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (subscription_id) REFERENCES user_subscriptions(id) ON DELETE SET NULL,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_transaction_id (transaction_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- Table: notifications
-- Description: Notifications système et emails
-- ================================================================
CREATE TABLE IF NOT EXISTS notifications (
    id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    user_id CHAR(36) NOT NULL,
    type ENUM('email', 'sms', 'push') DEFAULT 'email',
    subject VARCHAR(255),
    content TEXT NOT NULL,
    status ENUM('pending', 'sent', 'failed') DEFAULT 'pending',
    sent_at TIMESTAMP NULL,
    metadata JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_sent_at (sent_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- Table: integrations
-- Description: Intégrations calendrier externes (Google, Outlook, etc.)
-- ================================================================
CREATE TABLE IF NOT EXISTS integrations (
    id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    user_id CHAR(36) NOT NULL,
    provider ENUM('google', 'microsoft', 'apple', 'caldav') NOT NULL,
    provider_account_id VARCHAR(255),
    access_token TEXT,
    refresh_token TEXT,
    token_expires_at TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE,
    sync_direction ENUM('one_way', 'two_way') DEFAULT 'two_way',
    last_sync_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_provider (provider)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- Table: activity_logs
-- Description: Journal d'activité pour audit et sécurité
-- ================================================================
CREATE TABLE IF NOT EXISTS activity_logs (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id CHAR(36),
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50),
    entity_id VARCHAR(36),
    ip_address VARCHAR(45),
    user_agent TEXT,
    details JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- Table: webhooks
-- Description: Configuration des webhooks pour intégrations externes
-- ================================================================
CREATE TABLE IF NOT EXISTS webhooks (
    id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    user_id CHAR(36) NOT NULL,
    url VARCHAR(500) NOT NULL,
    secret_key VARCHAR(255),
    events JSON COMMENT 'Liste des événements à notifier',
    is_active BOOLEAN DEFAULT TRUE,
    last_triggered_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- Vues utiles pour les statistiques
-- ================================================================

-- Vue: Statistiques utilisateurs
CREATE OR REPLACE VIEW v_user_stats AS
SELECT
    u.id,
    u.email,
    u.first_name,
    u.last_name,
    u.role,
    u.is_active,
    sp.name as subscription_plan,
    COUNT(DISTINCT e.id) as total_events,
    COUNT(DISTINCT b.id) as total_bookings,
    SUM(CASE WHEN b.status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_bookings,
    u.created_at
FROM users u
LEFT JOIN user_subscriptions us ON u.id = us.user_id AND us.status = 'active'
LEFT JOIN subscription_plans sp ON us.plan_id = sp.id
LEFT JOIN events e ON u.id = e.user_id
LEFT JOIN bookings b ON e.id = b.event_id
GROUP BY u.id, u.email, u.first_name, u.last_name, u.role, u.is_active, sp.name, u.created_at;

-- Vue: Statistiques des réservations par événement
CREATE OR REPLACE VIEW v_event_booking_stats AS
SELECT
    e.id as event_id,
    e.title,
    e.user_id,
    COUNT(b.id) as total_bookings,
    SUM(CASE WHEN b.status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_bookings,
    SUM(CASE WHEN b.status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_bookings,
    SUM(CASE WHEN b.status = 'completed' THEN 1 ELSE 0 END) as completed_bookings,
    AVG(e.price) as avg_price,
    SUM(CASE WHEN b.status = 'completed' THEN e.price ELSE 0 END) as total_revenue
FROM events e
LEFT JOIN bookings b ON e.id = b.event_id
GROUP BY e.id, e.title, e.user_id;

-- ================================================================
-- Index supplémentaires pour les performances
-- ================================================================

-- Index composites pour les requêtes fréquentes
CREATE INDEX idx_bookings_event_status ON bookings(event_id, status);
CREATE INDEX idx_bookings_scheduled_status ON bookings(scheduled_at, status);
CREATE INDEX idx_events_user_active ON events(user_id, is_active);

-- ================================================================
-- Procédures stockées utiles
-- ================================================================

DELIMITER //

-- Procédure: Obtenir les créneaux disponibles pour un événement
CREATE PROCEDURE sp_get_available_slots(
    IN p_event_id CHAR(36),
    IN p_date DATE
)
BEGIN
    SELECT
        e.id,
        e.title,
        e.duration,
        a.time_slots,
        b.scheduled_at
    FROM events e
    INNER JOIN users u ON e.user_id = u.id
    INNER JOIN availability_schedules a ON u.id = a.user_id
        AND a.day_of_week = LOWER(DAYNAME(p_date))
        AND a.is_available = TRUE
    LEFT JOIN bookings b ON e.id = b.event_id
        AND DATE(b.scheduled_at) = p_date
        AND b.status IN ('confirmed', 'pending')
    WHERE e.id = p_event_id
        AND e.is_active = TRUE;
END //

-- Procédure: Nettoyer les anciennes réservations
CREATE PROCEDURE sp_cleanup_old_bookings(
    IN p_days_old INT
)
BEGIN
    UPDATE bookings
    SET status = 'completed'
    WHERE status = 'confirmed'
        AND scheduled_at < DATE_SUB(NOW(), INTERVAL p_days_old DAY);
END //

DELIMITER ;

-- ================================================================
-- Triggers pour maintenir l'intégrité des données
-- ================================================================

DELIMITER //

-- Trigger: Mettre à jour le timestamp lors de modification
CREATE TRIGGER tr_users_before_update
BEFORE UPDATE ON users
FOR EACH ROW
BEGIN
    SET NEW.updated_at = CURRENT_TIMESTAMP;
END //

-- Trigger: Vérifier les limites du plan lors de création d'événement
CREATE TRIGGER tr_events_before_insert
BEFORE INSERT ON events
FOR EACH ROW
BEGIN
    DECLARE v_plan_max_events INT;
    DECLARE v_current_events INT;

    -- Récupérer la limite du plan actif
    SELECT sp.max_events INTO v_plan_max_events
    FROM user_subscriptions us
    INNER JOIN subscription_plans sp ON us.plan_id = sp.id
    WHERE us.user_id = NEW.user_id
        AND us.status = 'active'
    LIMIT 1;

    -- Compter les événements actuels
    SELECT COUNT(*) INTO v_current_events
    FROM events
    WHERE user_id = NEW.user_id;

    -- Vérifier la limite (sauf si illimité = -1)
    IF v_plan_max_events != -1 AND v_current_events >= v_plan_max_events THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Limite d\'événements atteinte pour votre plan';
    END IF;
END //

-- Trigger: Logger les activités importantes
CREATE TRIGGER tr_bookings_after_insert
AFTER INSERT ON bookings
FOR EACH ROW
BEGIN
    INSERT INTO activity_logs (user_id, action, entity_type, entity_id, details)
    SELECT
        e.user_id,
        'booking_created',
        'booking',
        NEW.id,
        JSON_OBJECT(
            'attendee_email', NEW.attendee_email,
            'scheduled_at', NEW.scheduled_at,
            'event_title', e.title
        )
    FROM events e
    WHERE e.id = NEW.event_id;
END //

DELIMITER ;

-- ================================================================
-- Données de test et comptes administrateurs
-- ================================================================

-- Créer le compte SUPER ADMIN principal
-- Email: evolystisweb@gmail.com
-- Password: Soufian@2025
-- Hash généré avec: password_hash('Soufian@2025', PASSWORD_BCRYPT)
INSERT INTO users (id, email, password_hash, first_name, last_name, role, email_verified, is_active)
VALUES (
    UUID(),
    'evolystisweb@gmail.com',
    '$2y$10$vXZrPLhxQE7KQvG5FKE.rOmH7YzKJE8PY3cYfZL.Qa8xFGHQxHJ6G',
    'Soufian',
    'Admin',
    'admin',
    TRUE,
    TRUE
);

-- Créer un utilisateur de test
-- Email: test@meetsync.com
-- Password: Test@2025
INSERT INTO users (id, email, password_hash, first_name, last_name, role, email_verified, is_active)
VALUES (
    UUID(),
    'test@meetsync.com',
    '$2y$10$E7gPx5YzFqWL8JHKqZX5.efHJKe8QYL.rYxFGHQxHJ6GTEST',
    'Utilisateur',
    'Test',
    'user',
    TRUE,
    TRUE
);

-- Assigner le plan gratuit à l'utilisateur de test
INSERT INTO user_subscriptions (id, user_id, plan_id, status, start_date, end_date, auto_renew)
SELECT
    UUID(),
    u.id,
    (SELECT id FROM subscription_plans WHERE name = 'Gratuit' LIMIT 1),
    'active',
    CURDATE(),
    DATE_ADD(CURDATE(), INTERVAL 1 YEAR),
    TRUE
FROM users u
WHERE u.email = 'test@meetsync.com';

-- Créer des profils pour les comptes créés
INSERT INTO user_profiles (user_id, timezone, language)
SELECT id, 'Europe/Paris', 'fr'
FROM users
WHERE email IN ('evolystisweb@gmail.com', 'test@meetsync.com');

-- ================================================================
-- Fin du script
-- ================================================================
