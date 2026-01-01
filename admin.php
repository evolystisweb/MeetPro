<?php
require_once 'includes/config.php';
requireLogin();
requireAdmin();

$pdo = getDB();

$stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
$totalUsers = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM events");
$totalEvents = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM bookings");
$totalBookings = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM user_subscriptions WHERE status = 'active'");
$activeSubscriptions = $stmt->fetch()['total'];

$tab = $_GET['tab'] ?? 'stats';

$pageTitle = 'Panel Admin';
include 'includes/header.php';
?>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900"><i class="fas fa-crown text-yellow-500 mr-2"></i> Panel Administrateur</h1>
        <p class="text-gray-600">Gestion complète de la plateforme</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Utilisateurs</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1"><?= $totalUsers ?></p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-users text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Événements</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1"><?= $totalEvents ?></p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-calendar text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Réservations</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1"><?= $totalBookings ?></p>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <i class="fas fa-calendar-check text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Abonnements Actifs</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1"><?= $activeSubscriptions ?></p>
                </div>
                <div class="bg-orange-100 p-3 rounded-lg">
                    <i class="fas fa-crown text-orange-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border mb-6">
        <div class="flex border-b">
            <a href="?tab=stats" class="px-6 py-4 <?= $tab === 'stats' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600' ?>">
                <i class="fas fa-chart-bar mr-2"></i> Statistiques
            </a>
            <a href="?tab=users" class="px-6 py-4 <?= $tab === 'users' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600' ?>">
                <i class="fas fa-users mr-2"></i> Utilisateurs
            </a>
            <a href="?tab=events" class="px-6 py-4 <?= $tab === 'events' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600' ?>">
                <i class="fas fa-calendar mr-2"></i> Événements
            </a>
            <a href="?tab=plans" class="px-6 py-4 <?= $tab === 'plans' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600' ?>">
                <i class="fas fa-tags mr-2"></i> Plans
            </a>
        </div>

        <div class="p-6">
            <?php if ($tab === 'stats'): ?>
                <?php include 'admin/stats.php'; ?>
            <?php elseif ($tab === 'users'): ?>
                <?php include 'admin/users.php'; ?>
            <?php elseif ($tab === 'events'): ?>
                <?php include 'admin/events.php'; ?>
            <?php elseif ($tab === 'plans'): ?>
                <?php include 'admin/plans.php'; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
