<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_plan'])) {
    $planId = (int)$_POST['plan_id'];
    $price = (float)$_POST['price'];
    $isActive = isset($_POST['is_active']) ? 1 : 0;

    $stmt = $pdo->prepare("UPDATE subscription_plans SET price = ?, is_active = ? WHERE id = ?");
    $stmt->execute([$price, $isActive, $planId]);

    $_SESSION['success'] = "Plan mis à jour";
    redirect('/admin.php?tab=plans');
}

$stmt = $pdo->query("
    SELECT sp.*, COUNT(us.id) as subscriber_count
    FROM subscription_plans sp
    LEFT JOIN user_subscriptions us ON sp.id = us.plan_id AND us.status = 'active'
    GROUP BY sp.id
    ORDER BY sp.sort_order
");
$plans = $stmt->fetchAll();
?>

<div>
    <h3 class="text-xl font-bold text-gray-900 mb-4">Gestion des Plans d'Abonnement</h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php foreach ($plans as $plan): ?>
            <div class="bg-gray-50 rounded-lg p-6 border">
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">

                    <div>
                        <h4 class="text-xl font-bold text-gray-900"><?= escape($plan['name']) ?></h4>
                        <p class="text-sm text-gray-600 mt-1"><?= escape($plan['description']) ?></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prix (€)</label>
                        <input type="number" name="price" value="<?= $plan['price'] ?>" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <div class="space-y-2 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700">Événements max</span>
                            <span class="font-semibold"><?= $plan['max_events'] === -1 ? 'Illimité' : $plan['max_events'] ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700">Réservations/mois</span>
                            <span class="font-semibold"><?= $plan['max_bookings_per_month'] === -1 ? 'Illimité' : $plan['max_bookings_per_month'] ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700">Abonnés actifs</span>
                            <span class="font-semibold text-blue-600"><?= $plan['subscriber_count'] ?></span>
                        </div>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" <?= $plan['is_active'] ? 'checked' : '' ?> class="mr-2">
                            <span class="text-sm font-medium text-gray-700">Plan actif</span>
                        </label>
                    </div>

                    <button type="submit" name="update_plan" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Mettre à jour
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>
