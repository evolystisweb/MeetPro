<?php
/**
 * API Test Endpoint
 * Test la connexion à la base de données et la configuration PHP
 */

require_once 'config/cors.php';
require_once 'config/database.php';

$response = [
    'success' => true,
    'timestamp' => date('Y-m-d H:i:s'),
    'php_version' => phpversion(),
    'server_info' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
    'database' => 'Not tested'
];

try {
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT COUNT(*) as count FROM users";
    $stmt = $db->query($query);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $response['database'] = 'Connected';
    $response['users_count'] = $result['count'];

} catch (Exception $e) {
    $response['database'] = 'Error: ' . $e->getMessage();
}

http_response_code(200);
echo json_encode($response, JSON_PRETTY_PRINT);
?>
