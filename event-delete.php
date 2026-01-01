<?php
require_once 'includes/config.php';
requireLogin();

$eventId = $_GET['id'] ?? '';
if (empty($eventId)) {
    redirect('/dashboard.php');
}

$pdo = getDB();
$stmt = $pdo->prepare("DELETE FROM events WHERE id = ? AND user_id = ?");

if ($stmt->execute([$eventId, $_SESSION['user_id']])) {
    $_SESSION['success'] = "Événement supprimé avec succès";
} else {
    $_SESSION['error'] = "Erreur lors de la suppression";
}

redirect('/dashboard.php');
?>
