<?php
/**
 * API Endpoint: Connexion utilisateur
 * Method: POST
 * Body: { "email": "", "password": "" }
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

    try {
        $query = "SELECT id, email, password_hash, first_name, last_name, role, is_active
                  FROM users WHERE email = :email LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $data->email);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Email ou mot de passe incorrect'
            ]);
            exit();
        }

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user['is_active']) {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'message' => 'Compte désactivé'
            ]);
            exit();
        }

        if (!Auth::verifyPassword($data->password, $user['password_hash'])) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Email ou mot de passe incorrect'
            ]);
            exit();
        }

        // Mettre à jour le dernier login
        $update_query = "UPDATE users SET last_login = NOW() WHERE id = :id";
        $update_stmt = $db->prepare($update_query);
        $update_stmt->bindParam(':id', $user['id']);
        $update_stmt->execute();

        // Générer le token
        $token = Auth::generateToken($user['id'], $user['email'], $user['role']);

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'token' => $token,
            'user' => [
                'id' => $user['id'],
                'email' => $user['email'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'role' => $user['role']
            ]
        ]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Erreur serveur'
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
