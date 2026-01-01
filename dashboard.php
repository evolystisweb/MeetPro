<?php
require_once 'includes/config.php';
requireLogin();

$pdo = getDB();
$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM events WHERE user_id = ?");
$stmt->execute([$userId]);
$totalEvents = $stmt->fetch()['total'];

$stmt = $pdo->prepare("
    SELECT COUNT(*) as total
    FROM bookings b
    JOIN events e ON b.event_id = e.id
    WHERE e.user_id = ?
");
$stmt->execute([$userId]);
$totalBookings = $stmt->fetch()['total'];

$stmt = $pdo->prepare("
    SELECT COUNT(*) as total
    FROM bookings b
    JOIN events e ON b.event_id = e.id
    WHERE e.user_id = ? AND b.status = 'pending'
");
$stmt->execute([$userId]);
$pendingBookings = $stmt->fetch()['total'];

$stmt = $pdo->prepare("
    SELECT e.*, COUNT(b.id) as booking_count
    FROM events e
    LEFT JOIN bookings b ON e.id = b.event_id
    WHERE e.user_id = ?
    GROUP BY e.id
    ORDER BY e.created_at DESC
");
$stmt->execute([$userId]);
$events = $stmt->fetchAll();

$pageTitle = 'Dashboard';
include 'includes/header.php';
?>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-600">Bienvenue, <?= escape($_SESSION['user_name']) ?></p>
        </div>
        <a href="/event-create.php" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-semibold">
            <i class="fas fa-plus mr-2"></i> Nouvel événement
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Événements</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1"><?= $totalEvents ?></p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-calendar text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Réservations</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1"><?= $totalBookings ?></p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">En Attente</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1"><?= $pendingBookings ?></p>
                </div>
                <div class="bg-orange-100 p-3 rounded-lg">
                    <i class="fas fa-clock text-orange-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border">
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold text-gray-900">Mes Événements</h2>
        </div>

        <?php if (empty($events)): ?>
            <div class="p-12 text-center">
                <i class="fas fa-calendar-alt text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucun événement</h3>
                <p class="text-gray-500 mb-6">Créez votre premier événement pour commencer à recevoir des réservations</p>
                <a href="/event-create.php" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                    Créer un événement
                </a>
            </div>
        <?php else: ?>
            <div class="divide-y">
                <?php foreach ($events as $event): ?>
                    <div class="p-6 hover:bg-gray-50">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900"><?= escape($event['title']) ?></h3>
                                    <?php if ($event['is_active']): ?>
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Actif</span>
                                    <?php else: ?>
                                        <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">Inactif</span>
                                    <?php endif; ?>
                                </div>

                                <p class="text-gray-600 mb-3"><?= escape($event['description']) ?></p>

                                <div class="flex items-center gap-6 text-sm text-gray-500">
                                    <span><i class="fas fa-clock mr-1"></i> <?= $event['duration'] ?> min</span>
                                    <span><i class="fas fa-map-marker-alt mr-1"></i> <?= ucfirst($event['location_type']) ?></span>
                                    <?php if ($event['price'] > 0): ?>
                                        <span><i class="fas fa-euro-sign mr-1"></i> <?= number_format($event['price'], 2) ?> €</span>
                                    <?php else: ?>
                                        <span class="text-green-600"><i class="fas fa-gift mr-1"></i> Gratuit</span>
                                    <?php endif; ?>
                                    <span><i class="fas fa-calendar-check mr-1"></i> <?= $event['booking_count'] ?> réservations</span>
                                </div>

                                <div class="mt-3 flex items-center gap-2">
                                    <input type="text" value="<?= SITE_URL ?>/booking.php?id=<?= $event['id'] ?>" readonly class="text-sm bg-gray-50 border border-gray-300 rounded px-3 py-2 flex-1" id="link-<?= $event['id'] ?>">
                                    <button onclick="copyLink('<?= $event['id'] ?>')" class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300 text-sm">
                                        <i class="fas fa-copy"></i> Copier
                                    </button>
                                </div>
                            </div>

                            <div class="flex flex-col gap-2 ml-4">
                                <a href="/event-edit.php?id=<?= $event['id'] ?>" class="bg-blue-100 text-blue-700 px-4 py-2 rounded hover:bg-blue-200 text-center text-sm">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                                <a href="/event-bookings.php?id=<?= $event['id'] ?>" class="bg-green-100 text-green-700 px-4 py-2 rounded hover:bg-green-200 text-center text-sm">
                                    <i class="fas fa-list"></i> Réservations
                                </a>
                                <a href="/event-delete.php?id=<?= $event['id'] ?>" onclick="return confirm('Supprimer cet événement ?')" class="bg-red-100 text-red-700 px-4 py-2 rounded hover:bg-red-200 text-center text-sm">
                                    <i class="fas fa-trash"></i> Supprimer
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function copyLink(eventId) {
    const input = document.getElementById('link-' + eventId);
    input.select();
    document.execCommand('copy');
    alert('Lien copié !');
}
</script>

<?php include 'includes/footer.php'; ?>
