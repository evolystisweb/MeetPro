<?php
/**
 * Script d'Installation et Configuration Automatique de MeetSync
 *
 * Ce script configure automatiquement :
 * - La base de données MySQL
 * - Les tables et relations
 * - Les comptes administrateurs
 * - Les plans tarifaires
 *
 * IMPORTANT : Supprimer ce fichier après installation !
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuration
$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation MeetSync</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 800px;
            width: 100%;
            padding: 40px;
        }
        h1 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 32px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 16px;
        }
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        .step {
            flex: 1;
            text-align: center;
            position: relative;
            color: #999;
        }
        .step.active {
            color: #667eea;
            font-weight: bold;
        }
        .step.completed {
            color: #10b981;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }
        .btn-secondary {
            background: #e5e7eb;
            color: #333;
        }
        .success {
            background: #d1fae5;
            color: #065f46;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #10b981;
        }
        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #ef4444;
        }
        .info {
            background: #dbeafe;
            color: #1e40af;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #3b82f6;
        }
        .credentials {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .credentials h3 {
            color: #667eea;
            margin-bottom: 15px;
        }
        .credential-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .credential-item:last-child {
            border-bottom: none;
        }
        .credential-label {
            color: #666;
            font-weight: 500;
        }
        .credential-value {
            color: #333;
            font-family: monospace;
            background: white;
            padding: 4px 8px;
            border-radius: 4px;
        }
        .buttons {
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }
        .warning {
            background: #fef3c7;
            color: #92400e;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #f59e0b;
        }
        code {
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Installation MeetSync</h1>
        <p class="subtitle">Configuration automatique de votre plateforme</p>

        <div class="step-indicator">
            <div class="step <?php echo $step >= 1 ? 'active' : ''; ?> <?php echo $step > 1 ? 'completed' : ''; ?>">
                1. Prérequis
            </div>
            <div class="step <?php echo $step >= 2 ? 'active' : ''; ?> <?php echo $step > 2 ? 'completed' : ''; ?>">
                2. Base de données
            </div>
            <div class="step <?php echo $step >= 3 ? 'active' : ''; ?> <?php echo $step > 3 ? 'completed' : ''; ?>">
                3. Installation
            </div>
            <div class="step <?php echo $step >= 4 ? 'active' : ''; ?>">
                4. Terminé
            </div>
        </div>

        <?php if ($step === 1): ?>
            <!-- ÉTAPE 1: Vérification des prérequis -->
            <h2>Vérification des prérequis</h2>

            <?php
            $checks = [
                'PHP Version >= 7.4' => version_compare(PHP_VERSION, '7.4.0', '>='),
                'Extension PDO' => extension_loaded('pdo'),
                'Extension PDO MySQL' => extension_loaded('pdo_mysql'),
                'Extension MBString' => extension_loaded('mbstring'),
                'Extension JSON' => extension_loaded('json'),
                'Extension OpenSSL' => extension_loaded('openssl'),
            ];

            $all_passed = true;
            foreach ($checks as $check => $passed) {
                if (!$passed) $all_passed = false;
                echo '<div class="' . ($passed ? 'success' : 'error') . '">';
                echo ($passed ? '✅ ' : '❌ ') . $check;
                echo '</div>';
            }
            ?>

            <?php if ($all_passed): ?>
                <div class="info">
                    ✅ Tous les prérequis sont satisfaits ! Vous pouvez continuer l'installation.
                </div>
                <div class="buttons">
                    <button class="btn" onclick="window.location.href='?step=2'">
                        Continuer →
                    </button>
                </div>
            <?php else: ?>
                <div class="error">
                    ❌ Certains prérequis ne sont pas satisfaits. Veuillez installer les extensions manquantes avant de continuer.
                </div>
            <?php endif; ?>

        <?php elseif ($step === 2): ?>
            <!-- ÉTAPE 2: Configuration de la base de données -->
            <h2>Configuration de la base de données</h2>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $db_host = $_POST['db_host'] ?? 'localhost';
                $db_name = $_POST['db_name'] ?? 'meetsync';
                $db_user = $_POST['db_user'] ?? 'root';
                $db_pass = $_POST['db_pass'] ?? '';

                try {
                    // Tester la connexion
                    $dsn = "mysql:host=$db_host;charset=utf8mb4";
                    $pdo = new PDO($dsn, $db_user, $db_pass, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                    ]);

                    // Créer la base de données si elle n'existe pas
                    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                    $pdo->exec("USE `$db_name`");

                    // Sauvegarder la configuration
                    $config_content = "<?php
class Database {
    private \$host = '$db_host';
    private \$db_name = '$db_name';
    private \$username = '$db_user';
    private \$password = '$db_pass';
    private \$charset = 'utf8mb4';
    public \$conn;

    public function getConnection() {
        \$this->conn = null;
        try {
            \$dsn = \"mysql:host=\" . \$this->host . \";dbname=\" . \$this->db_name . \";charset=\" . \$this->charset;
            \$options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            \$this->conn = new PDO(\$dsn, \$this->username, \$this->password, \$options);
        } catch(PDOException \$exception) {
            error_log(\"Erreur de connexion: \" . \$exception->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Erreur de connexion à la base de données'
            ]);
            exit();
        }
        return \$this->conn;
    }

    public function closeConnection() {
        \$this->conn = null;
    }
}
?>";

                    file_put_contents('../config/database.php', $config_content);

                    // Sauvegarder les paramètres pour l'étape suivante
                    file_put_contents('../install/.db_config', json_encode([
                        'host' => $db_host,
                        'name' => $db_name,
                        'user' => $db_user,
                        'pass' => $db_pass
                    ]));

                    echo '<div class="success">✅ Connexion à la base de données réussie !</div>';
                    echo '<div class="info">La configuration a été sauvegardée dans <code>api/config/database.php</code></div>';
                    echo '<div class="buttons">';
                    echo '<button class="btn" onclick="window.location.href=\'?step=3\'">Continuer →</button>';
                    echo '</div>';

                } catch (PDOException $e) {
                    echo '<div class="error">❌ Erreur de connexion : ' . htmlspecialchars($e->getMessage()) . '</div>';
                }
            }
            ?>

            <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST' || isset($e)): ?>
            <form method="POST">
                <div class="form-group">
                    <label>Hôte MySQL</label>
                    <input type="text" name="db_host" value="localhost" required>
                </div>

                <div class="form-group">
                    <label>Nom de la base de données</label>
                    <input type="text" name="db_name" value="meetsync" required>
                </div>

                <div class="form-group">
                    <label>Utilisateur MySQL</label>
                    <input type="text" name="db_user" value="root" required>
                </div>

                <div class="form-group">
                    <label>Mot de passe MySQL</label>
                    <input type="password" name="db_pass">
                </div>

                <div class="buttons">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='?step=1'">
                        ← Retour
                    </button>
                    <button type="submit" class="btn">Tester la connexion →</button>
                </div>
            </form>
            <?php endif; ?>

        <?php elseif ($step === 3): ?>
            <!-- ÉTAPE 3: Installation des tables -->
            <h2>Installation de la base de données</h2>

            <?php
            if (file_exists('../install/.db_config')) {
                $config = json_decode(file_get_contents('../install/.db_config'), true);

                try {
                    $dsn = "mysql:host={$config['host']};dbname={$config['name']};charset=utf8mb4";
                    $pdo = new PDO($dsn, $config['user'], $config['pass'], [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                    ]);

                    // Lire et exécuter le script SQL
                    $sql_file = '../../database/mysql_schema.sql';
                    if (file_exists($sql_file)) {
                        $sql = file_get_contents($sql_file);

                        // Exécuter le script
                        $pdo->exec($sql);

                        echo '<div class="success">✅ Base de données installée avec succès !</div>';
                        echo '<div class="info">
                            <strong>Éléments créés :</strong>
                            <ul style="margin-top: 10px; padding-left: 20px;">
                                <li>14 tables avec relations</li>
                                <li>Index optimisés</li>
                                <li>Triggers automatiques</li>
                                <li>Vues pour statistiques</li>
                                <li>Procédures stockées</li>
                                <li>3 plans tarifaires</li>
                                <li>Comptes administrateurs</li>
                            </ul>
                        </div>';

                        // Vérifier que les comptes ont été créés
                        $stmt = $pdo->query("SELECT email, role FROM users WHERE role = 'admin'");
                        $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if (count($admins) > 0) {
                            echo '<div class="credentials">';
                            echo '<h3>🔐 Comptes créés</h3>';

                            foreach ($admins as $admin) {
                                if ($admin['email'] === 'evolystisweb@gmail.com') {
                                    echo '<div style="background: white; padding: 15px; border-radius: 8px; margin-bottom: 15px;">';
                                    echo '<h4 style="color: #667eea; margin-bottom: 10px;">👑 Super Admin</h4>';
                                    echo '<div class="credential-item">';
                                    echo '<span class="credential-label">Email:</span>';
                                    echo '<span class="credential-value">evolystisweb@gmail.com</span>';
                                    echo '</div>';
                                    echo '<div class="credential-item">';
                                    echo '<span class="credential-label">Mot de passe:</span>';
                                    echo '<span class="credential-value">Soufian@2025</span>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                            }

                            // Utilisateur de test
                            $stmt = $pdo->query("SELECT email FROM users WHERE email = 'test@meetsync.com'");
                            if ($stmt->rowCount() > 0) {
                                echo '<div style="background: white; padding: 15px; border-radius: 8px;">';
                                echo '<h4 style="color: #10b981; margin-bottom: 10px;">👤 Utilisateur Test</h4>';
                                echo '<div class="credential-item">';
                                echo '<span class="credential-label">Email:</span>';
                                echo '<span class="credential-value">test@meetsync.com</span>';
                                echo '</div>';
                                echo '<div class="credential-item">';
                                echo '<span class="credential-label">Mot de passe:</span>';
                                echo '<span class="credential-value">Test@2025</span>';
                                echo '</div>';
                                echo '</div>';
                            }

                            echo '</div>';
                        }

                        echo '<div class="buttons">';
                        echo '<button class="btn" onclick="window.location.href=\'?step=4\'">Terminer →</button>';
                        echo '</div>';

                    } else {
                        echo '<div class="error">❌ Fichier SQL non trouvé : ' . $sql_file . '</div>';
                    }

                } catch (PDOException $e) {
                    echo '<div class="error">❌ Erreur lors de l\'installation : ' . htmlspecialchars($e->getMessage()) . '</div>';
                    echo '<div class="buttons">';
                    echo '<button class="btn btn-secondary" onclick="window.location.href=\'?step=2\'">← Retour</button>';
                    echo '</div>';
                }
            } else {
                echo '<div class="error">❌ Configuration manquante. Veuillez recommencer depuis l\'étape 2.</div>';
                echo '<div class="buttons">';
                echo '<button class="btn" onclick="window.location.href=\'?step=2\'">← Retour</button>';
                echo '</div>';
            }
            ?>

        <?php elseif ($step === 4): ?>
            <!-- ÉTAPE 4: Installation terminée -->
            <h2>🎉 Installation terminée !</h2>

            <div class="success">
                ✅ MeetSync a été installé avec succès sur votre serveur !
            </div>

            <div class="warning">
                <strong>⚠️ IMPORTANT - Sécurité :</strong><br>
                Pour des raisons de sécurité, supprimez immédiatement le dossier <code>api/install/</code> de votre serveur :
                <br><br>
                <code>rm -rf api/install/</code>
            </div>

            <div class="credentials">
                <h3>🔑 Accès à votre plateforme</h3>

                <div style="background: white; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                    <h4 style="color: #667eea; margin-bottom: 10px;">👑 Compte Super Admin</h4>
                    <div class="credential-item">
                        <span class="credential-label">URL:</span>
                        <span class="credential-value"><?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/admin'; ?></span>
                    </div>
                    <div class="credential-item">
                        <span class="credential-label">Email:</span>
                        <span class="credential-value">evolystisweb@gmail.com</span>
                    </div>
                    <div class="credential-item">
                        <span class="credential-label">Mot de passe:</span>
                        <span class="credential-value">Soufian@2025</span>
                    </div>
                </div>

                <div style="background: white; padding: 15px; border-radius: 8px;">
                    <h4 style="color: #10b981; margin-bottom: 10px;">👤 Compte Test Utilisateur</h4>
                    <div class="credential-item">
                        <span class="credential-label">URL:</span>
                        <span class="credential-value"><?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/dashboard'; ?></span>
                    </div>
                    <div class="credential-item">
                        <span class="credential-label">Email:</span>
                        <span class="credential-value">test@meetsync.com</span>
                    </div>
                    <div class="credential-item">
                        <span class="credential-label">Mot de passe:</span>
                        <span class="credential-value">Test@2025</span>
                    </div>
                </div>
            </div>

            <div class="info">
                <strong>📚 Prochaines étapes :</strong>
                <ol style="margin-top: 10px; padding-left: 20px;">
                    <li>Connectez-vous avec le compte super admin</li>
                    <li>Configurez les plans tarifaires selon vos besoins</li>
                    <li>Personnalisez l'apparence de votre plateforme</li>
                    <li>Configurez les emails et notifications</li>
                    <li>Installez un certificat SSL (HTTPS)</li>
                </ol>
            </div>

            <div class="buttons">
                <a href="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/admin'; ?>" class="btn" style="text-decoration: none; display: inline-block;">
                    Accéder au Dashboard Admin
                </a>
            </div>

            <?php
            // Nettoyer les fichiers temporaires
            if (file_exists('../install/.db_config')) {
                @unlink('../install/.db_config');
            }
            ?>

        <?php endif; ?>
    </div>
</body>
</html>
