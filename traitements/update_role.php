<?php
session_start();
require_once "../includes/global.php";
require_once "../includes/isset_connection.php";
require_once "../includes/bdd.php";
require_once "../includes/only_admin_trmt.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id']) || empty($_POST['id'])) {
    header("Location: ../users?error=Utilisateur introuvable.");
    exit();
}

$id = (int) $_POST['id'];
$role = $_POST['role'] ?? '';

// Validation
if (!in_array($role, ['0', '1', '2'], true)) {
    header("Location: ../edit_user?id=$id&error=Rôle invalide.");
    exit();
}

// Un admin ne peut pas se retirer ses propres droits
if ($id === (int) $_SESSION['id'] && $role !== '1') {
    header("Location: ../edit_user?id=$id&error=Vous ne pouvez pas retirer votre propre rôle administrateur.");
    exit();
}

// Mise à jour
$stmt = $pdo->prepare("UPDATE user SET role = ? WHERE id = ?");
$ok = $stmt->execute([(int) $role, $id]);

if ($ok) {
    header("Location: ../edit_user?id=$id&success=Rôle mis à jour avec succès.");
    exit();
} else {
    header("Location: ../edit_user?id=$id&error=Erreur lors de la mise à jour du rôle.");
    exit();
}
