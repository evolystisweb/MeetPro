    <footer class="bg-gray-800 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4"><?= SITE_NAME ?></h3>
                    <p class="text-gray-400">Simplifiez vos rendez-vous professionnels</p>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Produit</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/#features" class="hover:text-white">Fonctionnalités</a></li>
                        <li><a href="/#pricing" class="hover:text-white">Tarifs</a></li>
                        <li><a href="/status.php" class="hover:text-white">Statut</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Support</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/documentation.php" class="hover:text-white">Documentation</a></li>
                        <li><a href="/help.php" class="hover:text-white">Aide</a></li>
                        <li><a href="/contact.php" class="hover:text-white">Contact</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Légal</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">CGU</a></li>
                        <li><a href="#" class="hover:text-white">Confidentialité</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; <?= date('Y') ?> <?= SITE_NAME ?>. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script>
        setTimeout(function() {
            const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
            alerts.forEach(alert => alert.style.display = 'none');
        }, 5000);
    </script>
</body>
</html>
