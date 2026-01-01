<?php
require_once 'includes/config.php';

if (isLoggedIn()) {
    redirect('/dashboard.php');
}

$pageTitle = 'Accueil';
include 'includes/header.php';
?>

<div class="bg-gradient-to-br from-blue-50 to-indigo-100 py-20">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6">
            Simplifiez vos rendez-vous<br>professionnels
        </h1>
        <p class="text-xl text-gray-700 mb-8 max-w-3xl mx-auto">
            La solution tout-en-un pour gérer vos événements et réservations.<br>
            Gagnez du temps et concentrez-vous sur l'essentiel.
        </p>
        <div class="flex gap-4 justify-center">
            <a href="/auth.php?register" class="bg-blue-600 text-white px-8 py-4 rounded-lg hover:bg-blue-700 font-semibold text-lg">
                <i class="fas fa-rocket mr-2"></i> Commencer gratuitement
            </a>
            <a href="#features" class="bg-white text-gray-900 px-8 py-4 rounded-lg hover:bg-gray-100 font-semibold text-lg border border-gray-300">
                <i class="fas fa-info-circle mr-2"></i> En savoir plus
            </a>
        </div>
    </div>
</div>

<div id="features" class="max-w-7xl mx-auto px-4 py-20">
    <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">Fonctionnalités Puissantes</h2>
        <p class="text-xl text-gray-600">Tout ce dont vous avez besoin pour gérer vos rendez-vous</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-lg shadow-sm border">
            <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-calendar-alt text-blue-600 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3">Gestion d'Événements</h3>
            <p class="text-gray-600">Créez et gérez facilement tous vos types de rendez-vous en quelques clics.</p>
        </div>

        <div class="bg-white p-8 rounded-lg shadow-sm border">
            <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-clock text-green-600 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3">Disponibilités Flexibles</h3>
            <p class="text-gray-600">Définissez vos horaires de disponibilité et laissez vos clients réserver.</p>
        </div>

        <div class="bg-white p-8 rounded-lg shadow-sm border">
            <div class="w-16 h-16 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-bell text-purple-600 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3">Notifications Automatiques</h3>
            <p class="text-gray-600">Rappels automatiques par email pour vous et vos participants.</p>
        </div>

        <div class="bg-white p-8 rounded-lg shadow-sm border">
            <div class="w-16 h-16 bg-orange-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-video text-orange-600 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3">Visioconférences Intégrées</h3>
            <p class="text-gray-600">Générez des liens de visio automatiquement pour vos rendez-vous.</p>
        </div>

        <div class="bg-white p-8 rounded-lg shadow-sm border">
            <div class="w-16 h-16 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-chart-line text-red-600 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3">Statistiques Détaillées</h3>
            <p class="text-gray-600">Suivez vos performances et analysez vos réservations.</p>
        </div>

        <div class="bg-white p-8 rounded-lg shadow-sm border">
            <div class="w-16 h-16 bg-teal-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-mobile-alt text-teal-600 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3">100% Responsive</h3>
            <p class="text-gray-600">Interface adaptée à tous les écrans, mobile, tablette et desktop.</p>
        </div>
    </div>
</div>

<div id="pricing" class="bg-gray-50 py-20">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Tarifs Simples et Transparents</h2>
            <p class="text-xl text-gray-600">Choisissez le plan qui correspond à vos besoins</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-lg shadow-sm border">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Gratuit</h3>
                <p class="text-gray-600 mb-6">Pour découvrir</p>
                <div class="mb-6">
                    <span class="text-4xl font-bold text-gray-900">0€</span>
                    <span class="text-gray-600">/mois</span>
                </div>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center"><i class="fas fa-check text-green-600 mr-2"></i> 3 types d'événements</li>
                    <li class="flex items-center"><i class="fas fa-check text-green-600 mr-2"></i> 50 réservations/mois</li>
                    <li class="flex items-center"><i class="fas fa-check text-green-600 mr-2"></i> Support email</li>
                </ul>
                <a href="/auth.php?register" class="block text-center bg-gray-200 text-gray-900 px-6 py-3 rounded-lg hover:bg-gray-300 font-semibold">
                    Commencer
                </a>
            </div>

            <div class="bg-blue-600 p-8 rounded-lg shadow-lg border-4 border-blue-700 relative">
                <div class="absolute top-0 right-0 bg-yellow-400 text-gray-900 px-4 py-1 rounded-bl-lg rounded-tr-lg text-sm font-bold">
                    Populaire
                </div>
                <h3 class="text-2xl font-bold text-white mb-2">Pro</h3>
                <p class="text-blue-100 mb-6">Pour les professionnels</p>
                <div class="mb-6">
                    <span class="text-4xl font-bold text-white">29.99€</span>
                    <span class="text-blue-100">/mois</span>
                </div>
                <ul class="space-y-3 mb-8 text-white">
                    <li class="flex items-center"><i class="fas fa-check mr-2"></i> 20 types d'événements</li>
                    <li class="flex items-center"><i class="fas fa-check mr-2"></i> 500 réservations/mois</li>
                    <li class="flex items-center"><i class="fas fa-check mr-2"></i> Support prioritaire</li>
                    <li class="flex items-center"><i class="fas fa-check mr-2"></i> Personnalisation avancée</li>
                    <li class="flex items-center"><i class="fas fa-check mr-2"></i> Intégrations calendrier</li>
                </ul>
                <a href="/auth.php?register" class="block text-center bg-white text-blue-600 px-6 py-3 rounded-lg hover:bg-gray-100 font-semibold">
                    Commencer
                </a>
            </div>

            <div class="bg-white p-8 rounded-lg shadow-sm border">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Business</h3>
                <p class="text-gray-600 mb-6">Pour les entreprises</p>
                <div class="mb-6">
                    <span class="text-4xl font-bold text-gray-900">79.99€</span>
                    <span class="text-gray-600">/mois</span>
                </div>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center"><i class="fas fa-check text-green-600 mr-2"></i> Événements illimités</li>
                    <li class="flex items-center"><i class="fas fa-check text-green-600 mr-2"></i> Réservations illimitées</li>
                    <li class="flex items-center"><i class="fas fa-check text-green-600 mr-2"></i> Support 24/7</li>
                    <li class="flex items-center"><i class="fas fa-check text-green-600 mr-2"></i> API complète</li>
                    <li class="flex items-center"><i class="fas fa-check text-green-600 mr-2"></i> White label</li>
                    <li class="flex items-center"><i class="fas fa-check text-green-600 mr-2"></i> Gestion équipe</li>
                </ul>
                <a href="/auth.php?register" class="block text-center bg-gray-200 text-gray-900 px-6 py-3 rounded-lg hover:bg-gray-300 font-semibold">
                    Commencer
                </a>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-20 text-center">
    <h2 class="text-4xl font-bold text-gray-900 mb-6">Prêt à démarrer ?</h2>
    <p class="text-xl text-gray-600 mb-8">Créez votre compte gratuitement en moins d'une minute</p>
    <a href="/auth.php?register" class="inline-block bg-blue-600 text-white px-8 py-4 rounded-lg hover:bg-blue-700 font-semibold text-lg">
        <i class="fas fa-rocket mr-2"></i> Commencer maintenant
    </a>
</div>

<?php include 'includes/footer.php'; ?>
