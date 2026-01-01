<?php
$stmt = $pdo->query("
    SELECT e.*, u.email as user_email, COUNT(b.id) as booking_count
    FROM events e
    JOIN users u ON e.user_id = u.id
    LEFT JOIN bookings b ON e.id = b.event_id
    GROUP BY e.id
    ORDER BY e.created_at DESC
    LIMIT 50
");
$events = $stmt->fetchAll();
?>

<div>
    <h3 class="text-xl font-bold text-gray-900 mb-4">Tous les Événements</h3>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titre</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Créateur</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durée</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prix</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Réservations</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Créé le</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php foreach ($events as $event): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-4">
                            <div class="font-medium text-gray-900"><?= escape($event['title']) ?></div>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-700"><?= escape($event['user_email']) ?></td>
                        <td class="px-4 py-4 text-sm"><?= $event['duration'] ?> min</td>
                        <td class="px-4 py-4 text-sm">
                            <?= $event['price'] > 0 ? number_format($event['price'], 2) . ' €' : 'Gratuit' ?>
                        </td>
                        <td class="px-4 py-4 text-sm"><?= $event['booking_count'] ?></td>
                        <td class="px-4 py-4">
                            <?php if ($event['is_active']): ?>
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Actif</span>
                            <?php else: ?>
                                <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">Inactif</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-500"><?= date('d/m/Y', strtotime($event['created_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
