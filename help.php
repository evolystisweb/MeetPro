<?php
require_once 'includes/config.php';
$pageTitle = 'Aide';
include 'includes/header.php';
?>

<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Centre d'Aide</h1>
        <p class="text-xl text-gray-600">Questions fréquentes et guides d'utilisation</p>
    </div>

    <div class="space-y-4">
        <div class="bg-white rounded-lg shadow-sm border">
            <button class="w-full p-6 text-left flex justify-between items-center" onclick="toggleFaq(1)">
                <h3 class="text-lg font-semibold text-gray-900">Comment créer mon premier événement ?</h3>
                <i class="fas fa-chevron-down text-gray-400" id="icon-1"></i>
            </button>
            <div class="hidden p-6 pt-0 border-t" id="content-1">
                <p class="text-gray-600">
                    1. Connectez-vous à votre compte<br>
                    2. Cliquez sur "Nouvel événement" dans le dashboard<br>
                    3. Remplissez les informations (titre, durée, type de lieu)<br>
                    4. Enregistrez et partagez le lien de réservation
                </p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border">
            <button class="w-full p-6 text-left flex justify-between items-center" onclick="toggleFaq(2)">
                <h3 class="text-lg font-semibold text-gray-900">Comment partager mon lien de réservation ?</h3>
                <i class="fas fa-chevron-down text-gray-400" id="icon-2"></i>
            </button>
            <div class="hidden p-6 pt-0 border-t" id="content-2">
                <p class="text-gray-600">
                    Dans votre dashboard, chaque événement affiche un lien unique. Cliquez sur "Copier" pour copier le lien et partagez-le avec vos clients par email, sur vos réseaux sociaux ou votre site web.
                </p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border">
            <button class="w-full p-6 text-left flex justify-between items-center" onclick="toggleFaq(3)">
                <h3 class="text-lg font-semibold text-gray-900">Puis-je accepter des paiements ?</h3>
                <i class="fas fa-chevron-down text-gray-400" id="icon-3"></i>
            </button>
            <div class="hidden p-6 pt-0 border-t" id="content-3">
                <p class="text-gray-600">
                    Oui, vous pouvez définir un prix pour vos événements. Les paiements seront intégrés dans une future mise à jour avec Stripe.
                </p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border">
            <button class="w-full p-6 text-left flex justify-between items-center" onclick="toggleFaq(4)">
                <h3 class="text-lg font-semibold text-gray-900">Comment gérer mes disponibilités ?</h3>
                <i class="fas fa-chevron-down text-gray-400" id="icon-4"></i>
            </button>
            <div class="hidden p-6 pt-0 border-t" id="content-4">
                <p class="text-gray-600">
                    La gestion des disponibilités sera bientôt disponible. Vous pourrez définir vos horaires de travail par jour de la semaine et bloquer certaines périodes.
                </p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border">
            <button class="w-full p-6 text-left flex justify-between items-center" onclick="toggleFaq(5)">
                <h3 class="text-lg font-semibold text-gray-900">Puis-je annuler ou reprogrammer un rendez-vous ?</h3>
                <i class="fas fa-chevron-down text-gray-400" id="icon-5"></i>
            </button>
            <div class="hidden p-6 pt-0 border-t" id="content-5">
                <p class="text-gray-600">
                    Oui, depuis la page "Réservations" de votre événement, vous pouvez changer le statut de chaque réservation (confirmé, annulé, terminé, etc.).
                </p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border">
            <button class="w-full p-6 text-left flex justify-between items-center" onclick="toggleFaq(6)">
                <h3 class="text-lg font-semibold text-gray-900">Comment changer de plan d'abonnement ?</h3>
                <i class="fas fa-chevron-down text-gray-400" id="icon-6"></i>
            </button>
            <div class="hidden p-6 pt-0 border-t" id="content-6">
                <p class="text-gray-600">
                    La gestion des abonnements sera disponible prochainement. Pour le moment, tous les comptes sont sur le plan gratuit.
                </p>
            </div>
        </div>
    </div>

    <div class="mt-12 bg-blue-50 rounded-lg p-8 text-center">
        <h3 class="text-2xl font-bold text-gray-900 mb-4">Vous ne trouvez pas de réponse ?</h3>
        <p class="text-gray-600 mb-6">Notre équipe support est là pour vous aider</p>
        <a href="/contact.php" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-semibold">
            <i class="fas fa-envelope mr-2"></i> Nous contacter
        </a>
    </div>
</div>

<script>
function toggleFaq(id) {
    const content = document.getElementById('content-' + id);
    const icon = document.getElementById('icon-' + id);

    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.classList.add('fa-chevron-up');
        icon.classList.remove('fa-chevron-down');
    } else {
        content.classList.add('hidden');
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    }
}
</script>

<?php include 'includes/footer.php'; ?>
