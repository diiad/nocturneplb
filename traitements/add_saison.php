<?php
require_once '../includes/bdd.php';

if (
    isset($_POST['annee_debut'], $_POST['annee_fin'], $_POST['prix']) &&
    !empty($_POST['annee_debut']) &&
    !empty($_POST['annee_fin']) &&
    !empty($_POST['prix'])
) {
    $annee_debut = htmlspecialchars($_POST['annee_debut']);
    $annee_fin = htmlspecialchars($_POST['annee_fin']);
    $prix = floatval($_POST['prix']);
    $active = isset($_POST['active']) ? 1 : 0;

    $check = $pdo->prepare("SELECT COUNT(*) FROM saison WHERE annee_debut = ? AND annee_fin = ?");
    $check->execute([$annee_debut, $annee_fin]);
    if ($check->fetchColumn() > 0) {
        echo "Une saison avec les mêmes années existe déjà.";
        exit();
    }

    if ($active === 1) {
        $pdo->query("UPDATE saison SET active = 0");
    }

    $stmt = $pdo->prepare("INSERT INTO saison (annee_debut, annee_fin, prix, active) VALUES (?, ?, ?, ?)");
    $stmt->execute([$annee_debut, $annee_fin, $prix, $active]);

    header('Location: ../stats.php');
    exit();
} else {
    echo "Tous les champs obligatoires ne sont pas remplis.";
}
?>
