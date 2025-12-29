<?php
/**
 * Utilitaires d'authentification avec sessions PHP
 * Version simplifiée sans dépendances Composer
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Auth {
    private static $secret_key = "MeetSync_Evolystis_Secret_Key_2025_Secure";

    /**
     * Générer un token simple (session basé)
     */
    public static function generateToken($user_id, $email, $role = 'user') {
        $token_data = [
            'user_id' => $user_id,
            'email' => $email,
            'role' => $role,
            'created_at' => time(),
            'expires_at' => time() + (60 * 60 * 24 * 30)
        ];

        $token = base64_encode(json_encode($token_data) . '|' . self::generateSignature($token_data));

        $_SESSION['auth_token'] = $token;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_role'] = $role;

        return $token;
    }

    /**
     * Générer une signature pour le token
     */
    private static function generateSignature($data) {
        return hash_hmac('sha256', json_encode($data), self::$secret_key);
    }

    /**
     * Vérifier et décoder un token
     */
    public static function validateToken($token) {
        try {
            $decoded = base64_decode($token);
            $parts = explode('|', $decoded);

            if (count($parts) !== 2) {
                return ['valid' => false, 'message' => 'Token invalide'];
            }

            $data = json_decode($parts[0], true);
            $signature = $parts[1];

            if (self::generateSignature($data) !== $signature) {
                return ['valid' => false, 'message' => 'Signature invalide'];
            }

            if ($data['expires_at'] < time()) {
                return ['valid' => false, 'message' => 'Token expiré'];
            }

            return [
                'valid' => true,
                'data' => (object)$data
            ];
        } catch (Exception $e) {
            return [
                'valid' => false,
                'message' => 'Token invalide: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtenir le token depuis les headers ou session
     */
    public static function getBearerToken() {
        if (function_exists('getallheaders')) {
            $headers = getallheaders();

            if (isset($headers['Authorization'])) {
                $matches = [];
                if (preg_match('/Bearer\s+(.*)$/i', $headers['Authorization'], $matches)) {
                    return $matches[1];
                }
            }
        }

        if (isset($_SESSION['auth_token'])) {
            return $_SESSION['auth_token'];
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

    /**
     * Déconnexion
     */
    public static function logout() {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
        return true;
    }
}
?>
