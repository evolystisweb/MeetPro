<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_user'])) {
    $userId = $_POST['user_id'];
    $isActive = $_POST['is_active'] === '1' ? 0 : 1;

    $stmt = $pdo->prepare("UPDATE users SET is_active = ? WHERE id = ?");
    $stmt->execute([$isActive, $userId]);

    $_SESSION['success'] = "Utilisateur mis à jour";
    redirect('/admin.php?tab=users');
}

$stmt = $pdo->query("
    SELECT u.*, sp.name as plan_name
    FROM users u
    LEFT JOIN user_subscriptions us ON u.id = us.user_id AND us.status = 'active'
    LEFT JOIN subscription_plans sp ON us.plan_id = sp.id
    ORDER BY u.created_at DESC
");
$users = $stmt->fetchAll();
?>

<div>
    <h3 class="text-xl font-bold text-gray-900 mb-4">Gestion des Utilisateurs</h3>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Utilisateur</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rôle</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Inscription</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php foreach ($users as $user): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-4">
                            <div class="font-medium text-gray-900"><?= escape($user['first_name']) ?> <?= escape($user['last_name']) ?></div>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-700"><?= escape($user['email']) ?></td>
                        <td class="px-4 py-4">
                            <?php if ($user['role'] === 'admin'): ?>
                                <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Admin</span>
                            <?php else: ?>
                                <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">User</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-4 text-sm"><?= $user['plan_name'] ?? 'Aucun' ?></td>
                        <td class="px-4 py-4 text-sm text-gray-500"><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                        <td class="px-4 py-4">
                            <?php if ($user['is_active']): ?>
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Actif</span>
                            <?php else: ?>
                                <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Inactif</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-4">
                            <form method="POST" class="inline">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <input type="hidden" name="is_active" value="<?= $user['is_active'] ?>">
                                <button type="submit" name="toggle_user" class="text-blue-600 hover:underline text-sm">
                                    <?= $user['is_active'] ? 'Désactiver' : 'Activer' ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
