<?php
/**
 * API Endpoint: Statistiques admin
 * Method: GET
 * Auth: Admin uniquement
 */

require_once '../config/cors.php';
require_once '../config/database.php';
require_once '../utils/auth.php';

$user = Auth::requireAdmin();

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Total utilisateurs
        $users_query = "SELECT
                        COUNT(*) as total_users,
                        SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_users
                        FROM users";
        $users_stmt = $db->query($users_query);
        $users_data = $users_stmt->fetch(PDO::FETCH_ASSOC);

        // Revenus
        $revenue_query = "SELECT
                         SUM(amount) as total_revenue,
                         SUM(CASE WHEN MONTH(created_at) = MONTH(CURRENT_DATE())
                             AND YEAR(created_at) = YEAR(CURRENT_DATE())
                             THEN amount ELSE 0 END) as monthly_revenue
                         FROM payments
                         WHERE status = 'completed'";
        $revenue_stmt = $db->query($revenue_query);
        $revenue_data = $revenue_stmt->fetch(PDO::FETCH_ASSOC);

        // Abonnements
        $subs_query = "SELECT
                      COUNT(*) as total_subscriptions,
                      SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_subscriptions
                      FROM user_subscriptions";
        $subs_stmt = $db->query($subs_query);
        $subs_data = $subs_stmt->fetch(PDO::FETCH_ASSOC);

        // Événements et réservations
        $activity_query = "SELECT
                          (SELECT COUNT(*) FROM events) as total_events,
                          (SELECT COUNT(*) FROM bookings) as total_bookings";
        $activity_stmt = $db->query($activity_query);
        $activity_data = $activity_stmt->fetch(PDO::FETCH_ASSOC);

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'stats' => [
                'totalUsers' => (int)$users_data['total_users'],
                'activeUsers' => (int)$users_data['active_users'],
                'totalRevenue' => (float)($revenue_data['total_revenue'] ?? 0),
                'monthlyRevenue' => (float)($revenue_data['monthly_revenue'] ?? 0),
                'totalSubscriptions' => (int)$subs_data['total_subscriptions'],
                'activeSubscriptions' => (int)$subs_data['active_subscriptions'],
                'totalEvents' => (int)$activity_data['total_events'],
                'totalBookings' => (int)$activity_data['total_bookings']
            ]
        ]);

    } catch (PDOException $e) {
        error_log("Erreur stats: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Erreur lors de la récupération des statistiques'
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée'
    ]);
}
?>
