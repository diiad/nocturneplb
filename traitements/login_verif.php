<?php
session_start();
require_once "../includes/global.php";
require_once "../includes/bdd.php";

if (isset($_SESSION['email'])) {
    header("Location: ../dashboard.php");
    exit();
}


if (!isset($_POST['email'], $_POST['password'])) {
    header('Location: ../login.php?error=veuillez remplir le formulaire');
    exit();
}

$email = trim($_POST['email']);
$password = $_POST['password'];

if (empty($email) || empty($password)) {
    header('Location: ../login.php?error=veuillez remplir tous les champs.');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email LIMIT 1");
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && isset($user['password'])) {
    if (password_verify($password, $user['password'])) {
        setcookie('email', $email, time() + 60 * 60 * 24 * 1, '/');
        $_SESSION['email'] = $email;
        $_SESSION['id'] = $user['id'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['phone'] = $user['phone'];
        $_SESSION['role'] = $user['role'];
        header('Location: ../dashboard.php');
        exit();
    } else {
        header('Location: ../login.php?error=Mot de passe incorrect');
        exit();
    }
} else {
    header('Location: ../login.php?error=utilisateur introuvable');
    exit();
}
?>