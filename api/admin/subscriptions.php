<?php
/**
 * API Endpoint: Gestion des abonnements (Admin)
 * Methods: GET, PUT
 * Auth: Admin uniquement
 */

require_once '../config/cors.php';
require_once '../config/database.php';
require_once '../utils/auth.php';

$user = Auth::requireAdmin();

$database = new Database();
$db = $database->getConnection();

// GET: Liste des abonnements
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $query = "SELECT
                  us.id,
                  us.user_id,
                  us.plan_id,
                  us.status,
                  us.start_date,
                  us.end_date,
                  us.auto_renew,
                  u.email as user_email,
                  sp.name as plan_name
                  FROM user_subscriptions us
                  INNER JOIN users u ON us.user_id = u.id
                  INNER JOIN subscription_plans sp ON us.plan_id = sp.id
                  ORDER BY us.created_at DESC";

        $stmt = $db->query($query);
        $subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'subscriptions' => $subscriptions
        ]);

    } catch (PDOException $e) {
        error_log("Erreur subscriptions list: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Erreur lors de la récupération des abonnements'
        ]);
    }
}

// PUT: Mettre à jour un abonnement
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));
    $subscription_id = $_GET['id'] ?? null;

    if (!$subscription_id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'ID d\'abonnement requis'
        ]);
        exit();
    }

    try {
        $update_fields = [];
        $params = [':id' => $subscription_id];

        if (isset($data->status)) {
            $update_fields[] = "status = :status";
            $params[':status'] = $data->status;
        }

        if (isset($data->auto_renew)) {
            $update_fields[] = "auto_renew = :auto_renew";
            $params[':auto_renew'] = $data->auto_renew ? 1 : 0;
        }

        if (empty($update_fields)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Aucune modification fournie'
            ]);
            exit();
        }

        $query = "UPDATE user_subscriptions SET " . implode(', ', $update_fields) . " WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->execute($params);

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Abonnement mis à jour avec succès'
        ]);

    } catch (PDOException $e) {
        error_log("Erreur subscription update: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Erreur lors de la mise à jour'
        ]);
    }
}

else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée'
    ]);
}
?>
