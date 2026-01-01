<?php
session_start();

define('DB_HOST', 'www.evomeetsync.evolystis.com');
define('DB_NAME', 'evolysti_evomeetsync');
define('DB_USER', 'evolysti_evomeetsync');
define('DB_PASS', 'Evolystis@2025@2025');
define('DB_CHARSET', 'utf8mb4');

define('SITE_NAME', 'MeetSync');
define('SITE_URL', 'https://www.evomeetsync.evolystis.com');

date_default_timezone_set('Europe/Paris');

function getDB() {
    static $pdo = null;

    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch(PDOException $e) {
            error_log("DB Error: " . $e->getMessage());
            die("Erreur de connexion à la base de données");
        }
    }

    return $pdo;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /auth.php');
        exit;
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: /dashboard.php');
        exit;
    }
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function escape($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function formatDate($date) {
    return date('d/m/Y H:i', strtotime($date));
}

function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }

    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}
?>
