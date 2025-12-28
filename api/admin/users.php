<?php
/**
 * API Endpoint: Gestion des utilisateurs (Admin)
 * Methods: GET, PUT
 * Auth: Admin uniquement
 */

require_once '../config/cors.php';
require_once '../config/database.php';
require_once '../utils/auth.php';

$user = Auth::requireAdmin();

$database = new Database();
$db = $database->getConnection();

// GET: Liste des utilisateurs
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $query = "SELECT
                  u.id,
                  u.email,
                  u.first_name,
                  u.last_name,
                  u.role,
                  u.is_active,
                  u.created_at,
                  sp.name as subscription_plan
                  FROM users u
                  LEFT JOIN user_subscriptions us ON u.id = us.user_id AND us.status = 'active'
                  LEFT JOIN subscription_plans sp ON us.plan_id = sp.id
                  ORDER BY u.created_at DESC";

        $stmt = $db->query($query);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'users' => $users
        ]);

    } catch (PDOException $e) {
        error_log("Erreur users list: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Erreur lors de la récupération des utilisateurs'
        ]);
    }
}

// PUT: Mettre à jour un utilisateur
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));
    $user_id = $_GET['id'] ?? null;

    if (!$user_id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'ID utilisateur requis'
        ]);
        exit();
    }

    try {
        $update_fields = [];
        $params = [':id' => $user_id];

        if (isset($data->is_active)) {
            $update_fields[] = "is_active = :is_active";
            $params[':is_active'] = $data->is_active ? 1 : 0;
        }

        if (isset($data->role)) {
            $update_fields[] = "role = :role";
            $params[':role'] = $data->role;
        }

        if (empty($update_fields)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Aucune modification fournie'
            ]);
            exit();
        }

        $query = "UPDATE users SET " . implode(', ', $update_fields) . " WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->execute($params);

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Utilisateur mis à jour avec succès'
        ]);

    } catch (PDOException $e) {
        error_log("Erreur user update: " . $e->getMessage());
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
