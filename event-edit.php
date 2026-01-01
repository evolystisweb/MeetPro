<?php
require_once 'includes/config.php';
requireLogin();

$eventId = $_GET['id'] ?? '';
if (empty($eventId)) {
    redirect('/dashboard.php');
}

$pdo = getDB();
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ? AND user_id = ?");
$stmt->execute([$eventId, $_SESSION['user_id']]);
$event = $stmt->fetch();

if (!$event) {
    $_SESSION['error'] = "Événement non trouvé";
    redirect('/dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $duration = (int)$_POST['duration'];
    $price = (float)$_POST['price'];
    $locationType = $_POST['location_type'];
    $locationDetails = trim($_POST['location_details'] ?? '');
    $color = $_POST['color'] ?? '#3b82f6';
    $isActive = isset($_POST['is_active']) ? 1 : 0;

    if (empty($title)) {
        $error = "Le titre est requis";
    } elseif ($duration < 15) {
        $error = "La durée minimum est de 15 minutes";
    } else {
        $stmt = $pdo->prepare("
            UPDATE events
            SET title = ?, description = ?, duration = ?, price = ?, location_type = ?, location_details = ?, color = ?, is_active = ?
            WHERE id = ? AND user_id = ?
        ");

        if ($stmt->execute([$title, $description, $duration, $price, $locationType, $locationDetails, $color, $isActive, $eventId, $_SESSION['user_id']])) {
            $_SESSION['success'] = "Événement modifié avec succès !";
            redirect('/dashboard.php');
        } else {
            $error = "Erreur lors de la modification";
        }
    }
}

$pageTitle = 'Modifier l\'événement';
include 'includes/header.php';
?>

<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="/dashboard.php" class="text-blue-600 hover:underline">
            <i class="fas fa-arrow-left mr-2"></i> Retour au dashboard
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Modifier l'événement</h1>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <?= escape($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Titre de l'événement *</label>
                <input type="text" name="title" value="<?= escape($event['title']) ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"><?= escape($event['description']) ?></textarea>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Durée (minutes) *</label>
                    <input type="number" name="duration" value="<?= $event['duration'] ?>" min="15" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prix (€)</label>
                    <input type="number" name="price" value="<?= $event['price'] ?>" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de lieu *</label>
                <select name="location_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="video" <?= $event['location_type'] === 'video' ? 'selected' : '' ?>>Visioconférence</option>
                    <option value="physical" <?= $event['location_type'] === 'physical' ? 'selected' : '' ?>>Sur place</option>
                    <option value="phone" <?= $event['location_type'] === 'phone' ? 'selected' : '' ?>>Téléphone</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Détails du lieu</label>
                <input type="text" name="location_details" value="<?= escape($event['location_details']) ?>" placeholder="Ex: Lien Zoom, adresse, numéro..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Couleur</label>
                <input type="color" name="color" value="<?= $event['color'] ?>" class="h-10 w-20 border border-gray-300 rounded">
            </div>

            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" <?= $event['is_active'] ? 'checked' : '' ?> class="mr-2">
                    <span class="text-sm font-medium text-gray-700">Événement actif</span>
                </label>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-semibold">
                    <i class="fas fa-save mr-2"></i> Enregistrer
                </button>
                <a href="/dashboard.php" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 font-semibold">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
