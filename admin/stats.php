<?php
$stmt = $pdo->query("
    SELECT DATE(created_at) as date, COUNT(*) as count
    FROM users
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    GROUP BY DATE(created_at)
    ORDER BY date DESC
    LIMIT 7
");
$recentUsers = $stmt->fetchAll();

$stmt = $pdo->query("
    SELECT DATE(created_at) as date, COUNT(*) as count
    FROM bookings
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    GROUP BY DATE(created_at)
    ORDER BY date DESC
    LIMIT 7
");
$recentBookings = $stmt->fetchAll();
?>

<div class="space-y-8">
    <div>
        <h3 class="text-xl font-bold text-gray-900 mb-4">Nouvelles Inscriptions (7 derniers jours)</h3>
        <div class="bg-gray-50 rounded-lg p-6">
            <?php if (empty($recentUsers)): ?>
                <p class="text-gray-500">Aucune donnée disponible</p>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($recentUsers as $stat): ?>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700"><?= date('d/m/Y', strtotime($stat['date'])) ?></span>
                            <div class="flex items-center">
                                <div class="bg-blue-200 h-8 rounded" style="width: <?= $stat['count'] * 20 ?>px"></div>
                                <span class="ml-3 font-semibold text-gray-900"><?= $stat['count'] ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div>
        <h3 class="text-xl font-bold text-gray-900 mb-4">Nouvelles Réservations (7 derniers jours)</h3>
        <div class="bg-gray-50 rounded-lg p-6">
            <?php if (empty($recentBookings)): ?>
                <p class="text-gray-500">Aucune donnée disponible</p>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($recentBookings as $stat): ?>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700"><?= date('d/m/Y', strtotime($stat['date'])) ?></span>
                            <div class="flex items-center">
                                <div class="bg-green-200 h-8 rounded" style="width: <?= $stat['count'] * 15 ?>px"></div>
                                <span class="ml-3 font-semibold text-gray-900"><?= $stat['count'] ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
