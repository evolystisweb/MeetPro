<?php
/**
 * API Endpoint: Gestion des plans tarifaires (Admin)
 * Methods: GET, POST, PUT
 * Auth: Admin uniquement
 */

require_once '../config/cors.php';
require_once '../config/database.php';
require_once '../utils/auth.php';

$user = Auth::requireAdmin();

$database = new Database();
$db = $database->getConnection();

// GET: Liste des plans
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $query = "SELECT * FROM subscription_plans ORDER BY sort_order ASC";
        $stmt = $db->query($query);
        $plans = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Décoder le JSON des features
        foreach ($plans as &$plan) {
            $plan['features'] = json_decode($plan['features']);
        }

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'plans' => $plans
        ]);

    } catch (PDOException $e) {
        error_log("Erreur plans list: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Erreur lors de la récupération des plans'
        ]);
    }
}

// POST: Créer un nouveau plan
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (empty($data->name) || !isset($data->price)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Nom et prix requis'
        ]);
        exit();
    }

    try {
        $features = json_encode($data->features ?? []);

        $query = "INSERT INTO subscription_plans
                 (name, description, price, billing_period, max_events,
                  max_bookings_per_month, features, is_active)
                 VALUES (:name, :description, :price, :billing_period,
                         :max_events, :max_bookings, :features, :is_active)";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $data->name);
        $stmt->bindParam(':description', $data->description);
        $stmt->bindParam(':price', $data->price);
        $stmt->bindParam(':billing_period', $data->billing_period);
        $stmt->bindParam(':max_events', $data->max_events);
        $stmt->bindParam(':max_bookings', $data->max_bookings_per_month);
        $stmt->bindParam(':features', $features);
        $stmt->bindParam(':is_active', $data->is_active);
        $stmt->execute();

        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Plan créé avec succès'
        ]);

    } catch (PDOException $e) {
        error_log("Erreur plan create: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Erreur lors de la création du plan'
        ]);
    }
}

// PUT: Mettre à jour un plan
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));
    $plan_id = $_GET['id'] ?? null;

    if (!$plan_id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'ID du plan requis'
        ]);
        exit();
    }

    try {
        $update_fields = [];
        $params = [':id' => $plan_id];

        if (isset($data->name)) {
            $update_fields[] = "name = :name";
            $params[':name'] = $data->name;
        }
        if (isset($data->price)) {
            $update_fields[] = "price = :price";
            $params[':price'] = $data->price;
        }
        if (isset($data->max_events)) {
            $update_fields[] = "max_events = :max_events";
            $params[':max_events'] = $data->max_events;
        }
        if (isset($data->max_bookings_per_month)) {
            $update_fields[] = "max_bookings_per_month = :max_bookings";
            $params[':max_bookings'] = $data->max_bookings_per_month;
        }
        if (isset($data->is_active)) {
            $update_fields[] = "is_active = :is_active";
            $params[':is_active'] = $data->is_active ? 1 : 0;
        }

        if (empty($update_fields)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Aucune modification fournie'
            ]);
            exit();
        }

        $query = "UPDATE subscription_plans SET " . implode(', ', $update_fields) . " WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->execute($params);

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Plan mis à jour avec succès'
        ]);

    } catch (PDOException $e) {
        error_log("Erreur plan update: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Erreur lors de la mise à jour du plan'
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
