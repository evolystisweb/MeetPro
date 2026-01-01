<?php
require_once 'includes/config.php';

$eventId = $_GET['id'] ?? '';
if (empty($eventId)) {
    redirect('/');
}

$pdo = getDB();
$stmt = $pdo->prepare("SELECT e.*, u.first_name, u.last_name, u.email as host_email FROM events e JOIN users u ON e.user_id = u.id WHERE e.id = ? AND e.is_active = TRUE");
$stmt->execute([$eventId]);
$event = $stmt->fetch();

if (!$event) {
    $pageTitle = 'Événement non trouvé';
    include 'includes/header.php';
    echo '<div class="max-w-3xl mx-auto px-4 py-16 text-center">';
    echo '<i class="fas fa-calendar-times text-gray-300 text-6xl mb-4"></i>';
    echo '<h1 class="text-3xl font-bold text-gray-900 mb-4">Événement non trouvé</h1>';
    echo '<p class="text-gray-600 mb-8">Cet événement n\'existe pas ou n\'est plus disponible.</p>';
    echo '<a href="/" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">Retour à l\'accueil</a>';
    echo '</div>';
    include 'includes/footer.php';
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendeeName = trim($_POST['attendee_name']);
    $attendeeEmail = trim($_POST['attendee_email']);
    $attendeePhone = trim($_POST['attendee_phone'] ?? '');
    $scheduledAt = $_POST['scheduled_at'];
    $notes = trim($_POST['notes'] ?? '');

    if (empty($attendeeName) || empty($attendeeEmail) || empty($scheduledAt)) {
        $error = "Veuillez remplir tous les champs obligatoires";
    } elseif (!filter_var($attendeeEmail, FILTER_VALIDATE_EMAIL)) {
        $error = "Email invalide";
    } else {
        $endTime = date('Y-m-d H:i:s', strtotime($scheduledAt) + ($event['duration'] * 60));
        $bookingId = bin2hex(random_bytes(18));

        $stmt = $pdo->prepare("
            INSERT INTO bookings (id, event_id, attendee_name, attendee_email, attendee_phone, scheduled_at, end_time, status, notes, attendee_timezone)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'confirmed', ?, 'Europe/Paris')
        ");

        if ($stmt->execute([$bookingId, $eventId, $attendeeName, $attendeeEmail, $attendeePhone, $scheduledAt, $endTime, $notes])) {
            $success = "Réservation confirmée ! Vous recevrez un email de confirmation.";
        } else {
            $error = "Erreur lors de la réservation";
        }
    }
}

$pageTitle = $event['title'] . ' - Réservation';
include 'includes/header.php';
?>

<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            <div class="bg-white rounded-lg shadow-sm border p-8 sticky top-8">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center text-2xl font-bold text-blue-600">
                        <?= strtoupper(substr($event['first_name'], 0, 1)) ?><?= strtoupper(substr($event['last_name'], 0, 1)) ?>
                    </div>
                    <div class="ml-4">
                        <h3 class="font-semibold text-gray-900"><?= escape($event['first_name']) ?> <?= escape($event['last_name']) ?></h3>
                        <p class="text-sm text-gray-500"><?= escape($event['host_email']) ?></p>
                    </div>
                </div>

                <h1 class="text-2xl font-bold text-gray-900 mb-4"><?= escape($event['title']) ?></h1>

                <?php if ($event['description']): ?>
                    <p class="text-gray-600 mb-6"><?= nl2br(escape($event['description'])) ?></p>
                <?php endif; ?>

                <div class="space-y-3 text-sm">
                    <div class="flex items-center text-gray-700">
                        <i class="fas fa-clock w-5 mr-3"></i>
                        <span><?= $event['duration'] ?> minutes</span>
                    </div>

                    <div class="flex items-center text-gray-700">
                        <i class="fas fa-map-marker-alt w-5 mr-3"></i>
                        <span>
                            <?php
                            $locationLabels = [
                                'video' => 'Visioconférence',
                                'physical' => 'Sur place',
                                'phone' => 'Par téléphone'
                            ];
                            echo $locationLabels[$event['location_type']];
                            ?>
                        </span>
                    </div>

                    <?php if ($event['location_details']): ?>
                        <div class="flex items-start text-gray-700">
                            <i class="fas fa-info-circle w-5 mr-3 mt-1"></i>
                            <span><?= escape($event['location_details']) ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="flex items-center text-gray-700">
                        <i class="fas fa-euro-sign w-5 mr-3"></i>
                        <span>
                            <?= $event['price'] > 0 ? number_format($event['price'], 2) . ' €' : 'Gratuit' ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="bg-white rounded-lg shadow-sm border p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Réserver un créneau</h2>

                <?php if ($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <?= escape($error) ?>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        <i class="fas fa-check-circle mr-2"></i> <?= escape($success) ?>
                    </div>
                    <a href="/" class="block text-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-semibold">
                        Retour à l'accueil
                    </a>
                <?php else: ?>
                    <form method="POST" class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nom complet *</label>
                            <input type="text" name="attendee_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" name="attendee_email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                            <input type="tel" name="attendee_phone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date et heure *</label>
                            <input type="datetime-local" name="scheduled_at" required min="<?= date('Y-m-d\TH:i') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes ou questions</label>
                            <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-semibold">
                            <i class="fas fa-calendar-check mr-2"></i> Confirmer la réservation
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
