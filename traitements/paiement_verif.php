<?php
session_start();
require_once "../includes/isset_connection.php";
require_once "../includes/bdd.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $search = trim($_POST['search'] ?? '');
    $montant = floatval(str_replace(',', '.', $_POST['montant'] ?? '0'));
    $saison_id = intval($_POST['saison_id'] ?? 0);

    if (empty($search) || $montant <= 0 || $saison_id <= 0) {
        header("Location: ../paiement.php?error=Tous les champs sont obligatoires");
        exit;
    }

    $stmt = $pdo->prepare("SELECT id FROM user WHERE email = :search OR phone = :search  OR instagram = :search LIMIT 1");
    $stmt->execute(['search' => $search]);
    $user = $stmt->fetch();

    if (!$user) {

        header("Location: ../paiement.php?error=Utilisateur non trouvé");
        exit;
    }

    $user_id = $user['id'];

    $insert = $pdo->prepare("INSERT INTO paiement (user_id, montant, saison_id, date_paiement) VALUES (:user_id, :montant, :saison_id, NOW())");
    $success = $insert->execute([
        'user_id' => $user_id,
        'montant' => $montant,
        'saison_id' => $saison_id
    ]);

    if ($success) {
        header("Location: ../paiement.php?success=Paiement ajouté avec succès");
    } else {

        header("Location: ../paiement.php?error=Erreur lors de l'enregistrement.");
    }
    exit;
} else {
    header("Location: ../paiement.php");
    exit;
}