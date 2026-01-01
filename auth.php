<?php
require_once 'includes/config.php';

if (isLoggedIn()) {
    redirect('/dashboard.php');
}

$isLogin = !isset($_GET['register']);
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = getDB();

    if (isset($_POST['register'])) {
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');

        if (empty($email) || empty($password)) {
            $error = "Email et mot de passe requis";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Email invalide";
        } elseif (strlen($password) < 8) {
            $error = "Le mot de passe doit contenir au moins 8 caractères";
        } else {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->fetch()) {
                $error = "Cet email est déjà utilisé";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $userId = bin2hex(random_bytes(18));

                $stmt = $pdo->prepare("
                    INSERT INTO users (id, email, password_hash, first_name, last_name, role, is_active, email_verified)
                    VALUES (?, ?, ?, ?, ?, 'user', TRUE, TRUE)
                ");

                if ($stmt->execute([$userId, $email, $hashedPassword, $firstName, $lastName])) {
                    $stmt = $pdo->prepare("
                        INSERT INTO user_profiles (user_id, timezone, language)
                        VALUES (?, 'Europe/Paris', 'fr')
                    ");
                    $stmt->execute([$userId]);

                    $stmt = $pdo->prepare("
                        INSERT INTO user_subscriptions (id, user_id, plan_id, status, start_date, end_date, auto_renew)
                        SELECT ?, ?, id, 'active', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 YEAR), TRUE
                        FROM subscription_plans WHERE name = 'Gratuit' LIMIT 1
                    ");
                    $subscriptionId = bin2hex(random_bytes(18));
                    $stmt->execute([$subscriptionId, $userId]);

                    $success = "Compte créé avec succès ! Vous pouvez vous connecter.";
                    $isLogin = true;
                } else {
                    $error = "Erreur lors de la création du compte";
                }
            }
        }
    } elseif (isset($_POST['login'])) {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            $error = "Email et mot de passe requis";
        } else {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_active = TRUE");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_role'] = $user['role'];

                $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                $stmt->execute([$user['id']]);

                redirect('/dashboard.php');
            } else {
                $error = "Email ou mot de passe incorrect";
            }
        }
    }
}

$pageTitle = $isLogin ? 'Connexion' : 'Inscription';
include 'includes/header.php';
?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900">
                <?= $isLogin ? 'Connexion' : 'Créer un compte' ?>
            </h2>
            <p class="mt-2 text-gray-600">
                <?= $isLogin ? 'Accédez à votre espace' : 'Commencez gratuitement' ?>
            </p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <?= escape($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                <?= escape($success) ?>
            </div>
        <?php endif; ?>

        <form class="mt-8 space-y-6" method="POST">
            <?php if (!$isLogin): ?>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Prénom</label>
                        <input type="text" name="first_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nom</label>
                        <input type="text" name="last_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            <?php endif; ?>

            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Mot de passe</label>
                <input type="password" name="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <?php if (!$isLogin): ?>
                    <p class="mt-1 text-xs text-gray-500">Au moins 8 caractères</p>
                <?php endif; ?>
            </div>

            <button type="submit" name="<?= $isLogin ? 'login' : 'register' ?>" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-semibold">
                <?= $isLogin ? 'Se connecter' : 'Créer mon compte' ?>
            </button>
        </form>

        <div class="text-center">
            <a href="?<?= $isLogin ? 'register' : 'login' ?>" class="text-blue-600 hover:underline">
                <?= $isLogin ? 'Créer un compte' : 'Déjà inscrit ? Se connecter' ?>
            </a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
