<?php
require_once 'includes/config.php';
requireLogin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = getDB();

    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $duration = (int)$_POST['duration'];
    $price = (float)$_POST['price'];
    $locationType = $_POST['location_type'];
    $locationDetails = trim($_POST['location_details'] ?? '');
    $color = $_POST['color'] ?? '#3b82f6';

    if (empty($title)) {
        $error = "Le titre est requis";
    } elseif ($duration < 15) {
        $error = "La durée minimum est de 15 minutes";
    } else {
        $eventId = bin2hex(random_bytes(18));
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $title)) . '-' . substr($eventId, 0, 8);

        $stmt = $pdo->prepare("
            INSERT INTO events (id, user_id, title, slug, description, duration, price, location_type, location_details, color, is_active)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, TRUE)
        ");

        if ($stmt->execute([$eventId, $_SESSION['user_id'], $title, $slug, $description, $duration, $price, $locationType, $locationDetails, $color])) {
            $_SESSION['success'] = "Événement créé avec succès !";
            redirect('/dashboard.php');
        } else {
            $error = "Erreur lors de la création de l'événement";
        }
    }
}

$pageTitle = 'Créer un événement';
include 'includes/header.php';
?>

<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="/dashboard.php" class="text-blue-600 hover:underline">
            <i class="fas fa-arrow-left mr-2"></i> Retour au dashboard
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Créer un événement</h1>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <?= escape($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Titre de l'événement *</label>
                <input type="text" name="title" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Durée (minutes) *</label>
                    <input type="number" name="duration" value="30" min="15" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prix (€)</label>
                    <input type="number" name="price" value="0" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de lieu *</label>
                <select name="location_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="video">Visioconférence</option>
                    <option value="physical">Sur place</option>
                    <option value="phone">Téléphone</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Détails du lieu</label>
                <input type="text" name="location_details" placeholder="Ex: Lien Zoom, adresse, numéro..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Couleur</label>
                <input type="color" name="color" value="#3b82f6" class="h-10 w-20 border border-gray-300 rounded">
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-semibold">
                    <i class="fas fa-plus mr-2"></i> Créer l'événement
                </button>
                <a href="/dashboard.php" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 font-semibold">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
