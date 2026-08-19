<?php
require_once 'includes/bdd.php';
require_once 'includes/only_admin.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit;
}

if (!isset($_POST['paiement_id'], $_POST['user_id'])) {
    die('Paramètres manquants');
}

$paiementId = (int) $_POST['paiement_id'];
$userId = (int) $_POST['user_id'];

// Sécurité basique : vérifier que le paiement existe
$stmt = $pdo->prepare('SELECT id FROM paiement WHERE id = ?');
$stmt->execute([$paiementId]);
$paiement = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$paiement) {
    die('Paiement introuvable');
}

// Suppression du paiement
$stmt = $pdo->prepare('DELETE FROM paiement WHERE id = ?');
$stmt->execute([$paiementId]);

// Redirection vers la liste des paiements du même utilisateur
header('Location: show_paiement.php?user_id=' . $userId);
exit;
