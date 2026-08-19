<?php
session_start();
require_once "../includes/global.php";
require_once "../includes/isset_connection.php";
require_once "../includes/bdd.php";


if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}


$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !checkdnsrr(substr(strrchr($email, "@"), 1), "MX")) {
    $errorString = urlencode("L'adresse email est invalide");
    header("Location: ../profil_edit.php?error=$errorString");
    exit();
}

if (!empty($phone) && !preg_match('/^(0[6-7])[0-9]{8}$/', $phone)) {
    $errorString = urlencode("Le numéro de téléphone est invalide.");
    header("Location: ../profil_edit.php?error=$errorString");
    exit();
}


$stmt = $pdo->prepare("UPDATE user SET email = :email, phone = :phone WHERE id = :id");
$stmt->execute([
    'email' => $email,
    'phone' => $phone,
    'id' => $_SESSION['id']
]);


$successMessage = urlencode("Profil mis à jour avec succès.");
header("Location: ../profil.php?success=$successMessage");
exit();
?>