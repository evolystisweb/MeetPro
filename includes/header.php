<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? escape($pageTitle) . ' - ' . SITE_NAME : SITE_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-blue-600">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        <?= SITE_NAME ?>
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    <?php if (isLoggedIn()): ?>
                        <a href="/dashboard.php" class="text-gray-700 hover:text-blue-600 px-3 py-2">
                            <i class="fas fa-th-large mr-1"></i> Dashboard
                        </a>
                        <?php if (isAdmin()): ?>
                            <a href="/admin.php" class="text-gray-700 hover:text-blue-600 px-3 py-2">
                                <i class="fas fa-crown mr-1"></i> Admin
                            </a>
                        <?php endif; ?>
                        <a href="/logout.php" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                            <i class="fas fa-sign-out-alt mr-1"></i> Déconnexion
                        </a>
                    <?php else: ?>
                        <a href="/contact.php" class="text-gray-700 hover:text-blue-600 px-3 py-2">Contact</a>
                        <a href="/auth.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-sign-in-alt mr-1"></i> Connexion
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                <?= escape($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <?= escape($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        </div>
    <?php endif; ?>
