<?php
require_once 'includes/config.php';
$pageTitle = 'Documentation';
include 'includes/header.php';
?>

<div class="max-w-6xl mx-auto px-4 py-12">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Documentation</h1>
        <p class="text-xl text-gray-600">Guide complet pour utiliser MeetSync</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <div class="md:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border p-6 sticky top-8">
                <h3 class="font-semibold text-gray-900 mb-4">Sommaire</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#intro" class="text-blue-600 hover:underline">Introduction</a></li>
                    <li><a href="#account" class="text-blue-600 hover:underline">Créer un compte</a></li>
                    <li><a href="#events" class="text-blue-600 hover:underline">Gérer les événements</a></li>
                    <li><a href="#bookings" class="text-blue-600 hover:underline">Réservations</a></li>
                    <li><a href="#share" class="text-blue-600 hover:underline">Partager vos liens</a></li>
                    <li><a href="#admin" class="text-blue-600 hover:underline">Panel admin</a></li>
                </ul>
            </div>
        </div>

        <div class="md:col-span-3 space-y-12">
            <section id="intro">
                <div class="bg-white rounded-lg shadow-sm border p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Introduction</h2>
                    <p class="text-gray-600 mb-4">
                        MeetSync est une plateforme de gestion de rendez-vous professionnels. Elle vous permet de créer différents types d'événements, de définir vos disponibilités et de partager des liens de réservation avec vos clients.
                    </p>
                    <p class="text-gray-600">
                        Ce guide vous aidera à démarrer rapidement et à tirer le meilleur parti de toutes les fonctionnalités.
                    </p>
                </div>
            </section>

            <section id="account">
                <div class="bg-white rounded-lg shadow-sm border p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Créer un compte</h2>
                    <ol class="list-decimal list-inside space-y-3 text-gray-600">
                        <li>Cliquez sur "Connexion" dans le menu</li>
                        <li>Sélectionnez "Créer un compte"</li>
                        <li>Remplissez vos informations (prénom, nom, email, mot de passe)</li>
                        <li>Cliquez sur "Créer mon compte"</li>
                        <li>Vous êtes automatiquement inscrit au plan gratuit</li>
                    </ol>
                </div>
            </section>

            <section id="events">
                <div class="bg-white rounded-lg shadow-sm border p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Gérer les événements</h2>

                    <h3 class="text-xl font-semibold text-gray-900 mb-3 mt-6">Créer un événement</h3>
                    <ol class="list-decimal list-inside space-y-2 text-gray-600 mb-6">
                        <li>Dans le dashboard, cliquez sur "Nouvel événement"</li>
                        <li>Titre : Donnez un nom à votre événement (ex: "Consultation 30 min")</li>
                        <li>Description : Ajoutez des détails sur le rendez-vous</li>
                        <li>Durée : Définissez la durée en minutes</li>
                        <li>Prix : Indiquez le tarif (0 pour gratuit)</li>
                        <li>Type de lieu : Choisissez entre visioconférence, sur place ou téléphone</li>
                        <li>Cliquez sur "Créer l'événement"</li>
                    </ol>

                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Modifier un événement</h3>
                    <p class="text-gray-600 mb-4">
                        Depuis le dashboard, cliquez sur "Modifier" sur l'événement concerné. Vous pouvez changer tous les paramètres et activer/désactiver l'événement.
                    </p>

                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Supprimer un événement</h3>
                    <p class="text-gray-600">
                        Cliquez sur "Supprimer" sur l'événement. Attention, cette action supprimera aussi toutes les réservations associées.
                    </p>
                </div>
            </section>

            <section id="bookings">
                <div class="bg-white rounded-lg shadow-sm border p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Gérer les réservations</h2>
                    <p class="text-gray-600 mb-4">
                        Cliquez sur "Réservations" pour voir toutes les demandes de rendez-vous pour un événement spécifique.
                    </p>

                    <h3 class="text-xl font-semibold text-gray-900 mb-3 mt-6">Statuts disponibles</h3>
                    <ul class="list-disc list-inside space-y-2 text-gray-600">
                        <li><strong>En attente</strong> : La réservation attend votre confirmation</li>
                        <li><strong>Confirmé</strong> : Le rendez-vous est validé</li>
                        <li><strong>Terminé</strong> : Le rendez-vous a eu lieu</li>
                        <li><strong>Annulé</strong> : Le rendez-vous a été annulé</li>
                        <li><strong>Absent</strong> : Le participant ne s'est pas présenté</li>
                    </ul>
                </div>
            </section>

            <section id="share">
                <div class="bg-white rounded-lg shadow-sm border p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Partager vos liens de réservation</h2>
                    <p class="text-gray-600 mb-4">
                        Chaque événement possède un lien unique que vous pouvez partager avec vos clients.
                    </p>

                    <h3 class="text-xl font-semibold text-gray-900 mb-3 mt-6">Comment partager</h3>
                    <ol class="list-decimal list-inside space-y-2 text-gray-600">
                        <li>Dans le dashboard, trouvez votre événement</li>
                        <li>Cliquez sur "Copier" à côté du lien</li>
                        <li>Partagez ce lien par email, SMS, réseaux sociaux ou sur votre site web</li>
                        <li>Vos clients pourront réserver directement via ce lien</li>
                    </ol>
                </div>
            </section>

            <section id="admin">
                <div class="bg-white rounded-lg shadow-sm border p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Panel Administrateur</h2>
                    <p class="text-gray-600 mb-4">
                        Si vous avez les droits d'administrateur, vous pouvez accéder au panel admin pour gérer la plateforme.
                    </p>

                    <h3 class="text-xl font-semibold text-gray-900 mb-3 mt-6">Fonctionnalités admin</h3>
                    <ul class="list-disc list-inside space-y-2 text-gray-600">
                        <li>Statistiques globales de la plateforme</li>
                        <li>Gestion des utilisateurs (activer/désactiver)</li>
                        <li>Vue d'ensemble de tous les événements</li>
                        <li>Gestion des plans d'abonnement</li>
                    </ul>
                </div>
            </section>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
