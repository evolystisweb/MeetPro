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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $bookingId = $_POST['booking_id'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("
        UPDATE bookings SET status = ?
        WHERE id = ? AND event_id = ?
    ");
    $stmt->execute([$status, $bookingId, $eventId]);

    $_SESSION['success'] = "Statut mis à jour";
    redirect("/event-bookings.php?id=$eventId");
}

$stmt = $pdo->prepare("
    SELECT * FROM bookings
    WHERE event_id = ?
    ORDER BY scheduled_at DESC
");
$stmt->execute([$eventId]);
$bookings = $stmt->fetchAll();

$pageTitle = 'Réservations - ' . $event['title'];
include 'includes/header.php';
?>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="/dashboard.php" class="text-blue-600 hover:underline">
            <i class="fas fa-arrow-left mr-2"></i> Retour au dashboard
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border mb-6 p-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2"><?= escape($event['title']) ?></h1>
        <p class="text-gray-600">Réservations de cet événement</p>
    </div>

    <?php if (empty($bookings)): ?>
        <div class="bg-white rounded-lg shadow-sm border p-12 text-center">
            <i class="fas fa-calendar-times text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucune réservation</h3>
            <p class="text-gray-500">Partagez le lien de votre événement pour recevoir des réservations</p>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Heure</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Participant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <?php foreach ($bookings as $booking): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?= formatDate($booking['scheduled_at']) ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900"><?= escape($booking['attendee_name']) ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900"><?= escape($booking['attendee_email']) ?></div>
                                    <?php if ($booking['attendee_phone']): ?>
                                        <div class="text-sm text-gray-500"><?= escape($booking['attendee_phone']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'confirmed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                        'completed' => 'bg-blue-100 text-blue-800',
                                        'no_show' => 'bg-gray-100 text-gray-800'
                                    ];
                                    $statusLabels = [
                                        'pending' => 'En attente',
                                        'confirmed' => 'Confirmé',
                                        'cancelled' => 'Annulé',
                                        'completed' => 'Terminé',
                                        'no_show' => 'Absent'
                                    ];
                                    ?>
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?= $statusColors[$booking['status']] ?>">
                                        <?= $statusLabels[$booking['status']] ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                        <select name="status" onchange="this.form.submit()" class="text-sm border border-gray-300 rounded px-2 py-1">
                                            <option value="">Changer...</option>
                                            <option value="pending">En attente</option>
                                            <option value="confirmed">Confirmé</option>
                                            <option value="completed">Terminé</option>
                                            <option value="cancelled">Annulé</option>
                                            <option value="no_show">Absent</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
