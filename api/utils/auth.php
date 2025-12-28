<?php
/**
 * Utilitaires d'authentification JWT
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth {
    private static $secret_key = "VOTRE_CLE_SECRETE_JWT_TRES_LONGUE_ET_SECURISEE";
    private static $issuer = "meetsync.com";
    private static $audience = "meetsync-users";
    private static $algorithm = "HS256";

    /**
     * Générer un token JWT
     */
    public static function generateToken($user_id, $email, $role = 'user') {
        $issued_at = time();
        $expiration_time = $issued_at + (60 * 60 * 24 * 30); // 30 jours

        $payload = [
            'iat' => $issued_at,
            'exp' => $expiration_time,
            'iss' => self::$issuer,
            'aud' => self::$audience,
            'data' => [
                'user_id' => $user_id,
                'email' => $email,
                'role' => $role
            ]
        ];

        return JWT::encode($payload, self::$secret_key, self::$algorithm);
    }

    /**
     * Vérifier et décoder un token JWT
     */
    public static function validateToken($token) {
        try {
            $decoded = JWT::decode($token, new Key(self::$secret_key, self::$algorithm));
            return [
                'valid' => true,
                'data' => $decoded->data
            ];
        } catch (Exception $e) {
            return [
                'valid' => false,
                'message' => 'Token invalide: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtenir le token depuis les headers
     */
    public static function getBearerToken() {
        $headers = getallheaders();

        if (isset($headers['Authorization'])) {
            $matches = [];
            if (preg_match('/Bearer\s+(.*)$/i', $headers['Authorization'], $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Vérifier l'authentification
     */
    public static function authenticate() {
        $token = self::getBearerToken();

        if (!$token) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Token manquant']);
            exit();
        }

        $validation = self::validateToken($token);

        if (!$validation['valid']) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => $validation['message']]);
            exit();
        }

        return $validation['data'];
    }

    /**
     * Vérifier si l'utilisateur est admin
     */
    public static function requireAdmin() {
        $user = self::authenticate();

        if ($user->role !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Accès refusé']);
            exit();
        }

        return $user;
    }

    /**
     * Hasher un mot de passe
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
    }

    /**
     * Vérifier un mot de passe
     */
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
}
?>
