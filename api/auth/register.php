<?php
/**
 * API Endpoint: Inscription utilisateur
 * Method: POST
 * Body: { "email": "", "password": "", "first_name": "", "last_name": "" }
 */

require_once '../config/cors.php';
require_once '../config/database.php';
require_once '../utils/auth.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (empty($data->email) || empty($data->password)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Email et mot de passe requis'
        ]);
        exit();
    }

    // Vérifier le format de l'email
    if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Format d\'email invalide'
        ]);
        exit();
    }

    // Vérifier la longueur du mot de passe
    if (strlen($data->password) < 8) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Le mot de passe doit contenir au moins 8 caractères'
        ]);
        exit();
    }

    try {
        // Vérifier si l'email existe déjà
        $check_query = "SELECT id FROM users WHERE email = :email";
        $check_stmt = $db->prepare($check_query);
        $check_stmt->bindParam(':email', $data->email);
        $check_stmt->execute();

        if ($check_stmt->rowCount() > 0) {
            http_response_code(409);
            echo json_encode([
                'success' => false,
                'message' => 'Cet email est déjà utilisé'
            ]);
            exit();
        }

        // Commencer une transaction
        $db->beginTransaction();

        // Créer l'utilisateur
        $user_id = bin2hex(random_bytes(16));
        $password_hash = Auth::hashPassword($data->password);
        $first_name = $data->first_name ?? '';
        $last_name = $data->last_name ?? '';

        $insert_query = "INSERT INTO users (id, email, password_hash, first_name, last_name)
                        VALUES (:id, :email, :password_hash, :first_name, :last_name)";
        $insert_stmt = $db->prepare($insert_query);
        $insert_stmt->bindParam(':id', $user_id);
        $insert_stmt->bindParam(':email', $data->email);
        $insert_stmt->bindParam(':password_hash', $password_hash);
        $insert_stmt->bindParam(':first_name', $first_name);
        $insert_stmt->bindParam(':last_name', $last_name);
        $insert_stmt->execute();

        // Créer le profil utilisateur
        $profile_query = "INSERT INTO user_profiles (user_id) VALUES (:user_id)";
        $profile_stmt = $db->prepare($profile_query);
        $profile_stmt->bindParam(':user_id', $user_id);
        $profile_stmt->execute();

        // Assigner le plan gratuit
        $plan_query = "SELECT id FROM subscription_plans WHERE name = 'Gratuit' LIMIT 1";
        $plan_stmt = $db->query($plan_query);
        $plan = $plan_stmt->fetch(PDO::FETCH_ASSOC);

        if ($plan) {
            $subscription_id = bin2hex(random_bytes(16));
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d', strtotime('+1 year'));

            $sub_query = "INSERT INTO user_subscriptions
                         (id, user_id, plan_id, status, start_date, end_date)
                         VALUES (:id, :user_id, :plan_id, 'active', :start_date, :end_date)";
            $sub_stmt = $db->prepare($sub_query);
            $sub_stmt->bindParam(':id', $subscription_id);
            $sub_stmt->bindParam(':user_id', $user_id);
            $sub_stmt->bindParam(':plan_id', $plan['id']);
            $sub_stmt->bindParam(':start_date', $start_date);
            $sub_stmt->bindParam(':end_date', $end_date);
            $sub_stmt->execute();
        }

        $db->commit();

        // Générer le token
        $token = Auth::generateToken($user_id, $data->email, 'user');

        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Compte créé avec succès',
            'token' => $token,
            'user' => [
                'id' => $user_id,
                'email' => $data->email,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'role' => 'user'
            ]
        ]);

    } catch (PDOException $e) {
        $db->rollBack();
        error_log("Erreur inscription: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Erreur lors de la création du compte'
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
