<?php
require_once 'includes/config.php';

$pdo = getDB();
$dbStatus = $pdo ? 'Opérationnel' : 'Erreur';

$pageTitle = 'Statut';
include 'includes/header.php';
?>

<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Statut des Services</h1>
        <p class="text-xl text-gray-600">État en temps réel de nos systèmes</p>
    </div>

    <div class="space-y-4">
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-check text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Application Web</h3>
                        <p class="text-sm text-gray-600">Interface utilisateur principale</p>
                    </div>
                </div>
                <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-semibold">
                    Opérationnel
                </span>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-check text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Base de Données</h3>
                        <p class="text-sm text-gray-600">Stockage des données</p>
                    </div>
                </div>
                <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-semibold">
                    <?= $dbStatus ?>
                </span>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-check text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">API</h3>
                        <p class="text-sm text-gray-600">Services backend</p>
                    </div>
                </div>
                <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-semibold">
                    Opérationnel
                </span>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-check text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Réservations</h3>
                        <p class="text-sm text-gray-600">Système de booking</p>
                    </div>
                </div>
                <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-semibold">
                    Opérationnel
                </span>
            </div>
        </div>
    </div>

    <div class="mt-12 bg-gray-50 rounded-lg p-8">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Disponibilité</h3>
        <div class="grid grid-cols-3 gap-6 text-center">
            <div>
                <p class="text-3xl font-bold text-green-600">99.9%</p>
                <p class="text-sm text-gray-600 mt-1">30 derniers jours</p>
            </div>
            <div>
                <p class="text-3xl font-bold text-green-600">99.8%</p>
                <p class="text-sm text-gray-600 mt-1">90 derniers jours</p>
            </div>
            <div>
                <p class="text-3xl font-bold text-green-600">99.7%</p>
                <p class="text-sm text-gray-600 mt-1">12 derniers mois</p>
            </div>
        </div>
    </div>

    <div class="mt-8 text-center text-sm text-gray-500">
        <p>Dernière mise à jour : <?= date('d/m/Y H:i:s') ?></p>
        <p class="mt-2">En cas de problème, contactez-nous sur <a href="/contact.php" class="text-blue-600 hover:underline">la page contact</a></p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
