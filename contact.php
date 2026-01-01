<?php
require_once 'includes/config.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = "Tous les champs sont requis";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email invalide";
    } else {
        $success = "Message envoyé avec succès ! Nous vous répondrons rapidement.";
    }
}

$pageTitle = 'Contact';
include 'includes/header.php';
?>

<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Contactez-nous</h1>
        <p class="text-xl text-gray-600">Une question ? Une suggestion ? Nous sommes là pour vous aider.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-white p-6 rounded-lg shadow-sm border text-center">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-envelope text-blue-600 text-xl"></i>
            </div>
            <h3 class="font-semibold text-gray-900 mb-2">Email</h3>
            <p class="text-gray-600 text-sm">contact@meetsync.com</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border text-center">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-phone text-green-600 text-xl"></i>
            </div>
            <h3 class="font-semibold text-gray-900 mb-2">Téléphone</h3>
            <p class="text-gray-600 text-sm">+33 1 23 45 67 89</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border text-center">
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-comments text-purple-600 text-xl"></i>
            </div>
            <h3 class="font-semibold text-gray-900 mb-2">Support</h3>
            <p class="text-gray-600 text-sm">24/7 disponible</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Envoyez-nous un message</h2>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <?= escape($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <i class="fas fa-check-circle mr-2"></i> <?= escape($success) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sujet *</label>
                <input type="text" name="subject" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                <textarea name="message" rows="6" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 font-semibold">
                <i class="fas fa-paper-plane mr-2"></i> Envoyer le message
            </button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
