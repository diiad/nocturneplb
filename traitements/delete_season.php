<?php
session_start();
require_once '../includes/bdd.php';

if ($_SESSION['role'] != 1) {
    header('Location: dashboard.php?error=action non autorisé');
    exit();
}

if (isset($_GET['id'])) {
    $seasonId = intval($_GET['id']);

    // Vérifie si la saison supprimée était active
    $stmtCheck = $pdo->prepare("SELECT active FROM saison WHERE id = ?");
    $stmtCheck->execute([$seasonId]);
    $isActive = $stmtCheck->fetchColumn();

    $stmt = $pdo->prepare("DELETE FROM saison WHERE id = ?");
    if ($stmt->execute([$seasonId])) {
        // Si la saison supprimée était active, définir la saison avec le plus grand ID comme active
        if ($isActive == 1) {
            $stmtUpdate = $pdo->prepare("UPDATE saison SET active = 1 WHERE id = (SELECT MAX(id) FROM saison)");
            $stmtResetOthers = $pdo->prepare("UPDATE saison SET active = 0 WHERE id != (SELECT MAX(id) FROM saison)");
            $stmtUpdate->execute();
            $stmtResetOthers->execute();
        }
        header("Location: ../stats.php?success=La saison a été supprimée avec succès");
        exit();
    } else {
        header( "Location: stats.php?error=Erreur lors de la suppression.");
    }
} else {
    header(" Location: stats.php?error=ID de saison non fourni.");
}

?>