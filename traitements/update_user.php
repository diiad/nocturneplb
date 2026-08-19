<?php
session_start();
require_once "../includes/global.php";
require_once "../includes/isset_connection.php";
require_once "../includes/bdd.php";
require_once "../includes/only_admin_trmt.php";

if (!isset($_POST['id']) || empty($_POST['id'])) {
    header("Location: ../users.php?error=Utilisateur introuvable.");
    exit();
}

$id = (int) $_POST['id'];

$nom = trim($_POST['nom'] ?? '');
$prenom = trim($_POST['prenom'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$commentaire = $_POST['commentaire'] ?? '';
$instagram = trim($_POST['instagram'] ?? '');
$instagram = str_replace(' ', '', $instagram);
if (substr($instagram, 0, 1) === '@') {
    $instagram = substr($instagram, 1);
}

// Validation

if (empty($nom) || empty($prenom) || empty($email)) {
    header("Location: ../edit_user.php?id=$id&error=Tous les champs sont obligatoires.");
    exit();
}

if (!preg_match("/^[a-zA-ZÀ-ÖØ-öø-ÿ\s'-]+$/u", $nom) || !preg_match("/^[a-zA-ZÀ-ÖØ-öø-ÿ\s'-]+$/u", $prenom)) {
    header("Location: ../edit_user.php?id=$id&error=Nom ou prénom invalide (caractères interdits).");
    exit();
}

if (mb_strlen($nom, 'UTF-8') < 2 || mb_strlen($nom, 'UTF-8') > 50 ||
    mb_strlen($prenom, 'UTF-8') < 2 || mb_strlen($prenom, 'UTF-8') > 50) {
    header("Location: ../edit_user.php?id=$id&error=Nom/Prénom doivent faire entre 2 et 50 caractères.");
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../edit_user.php?id=$id&error=Email invalide.");
    exit();
}

if (!preg_match('/^0[67][0-9]{8}$/', $phone)) {
    header("Location: ../edit_user.php?id=$id&error=Numéro de téléphone invalide.");
    exit();
}

if (
    filter_var($instagram, FILTER_VALIDATE_EMAIL) ||
    !preg_match('/^(?=.*[A-Za-z])(?!.*\.\.)(?!.*\.$)(?!^\.)[A-Za-z0-9._]{1,30}$/', $instagram)
) {
    header("Location: ../edit_user.php?id=$id&error=Identifiant Instagram invalide (lettres, chiffres, ., _ seulement).");
    exit();
}

// Mise à jour
$stmt = $pdo->prepare("UPDATE user SET nom = ?, prenom = ?, email = ?, phone = ?, instagram = ?, commentaire = ? WHERE id = ?");
$ok = $stmt->execute([$nom, $prenom, $email, $phone, $instagram, $commentaire, $id]);

if ($ok) {
    header("Location: ../edit_user.php?id=$id&success=Utilisateur mis à jour avec succès.");
    exit();
} else {
    header("Location: ../edit_user.php?id=$id&error=Erreur lors de la mise à jour.");
    exit();
}
