<?php
require_once '../includes/bdd.php';

$nom = $_POST['nom'] ?? '';
$prenom = $_POST['prenom'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$instagram = $_POST['instagram'] ?? '';

$nom = trim($nom);
$prenom = trim($prenom);
$phone = trim($phone);
$email = trim($email);
$instagram = trim($instagram);
$instagram = str_replace(' ', '', $instagram);
if (substr($instagram, 0, 1) === '@') {
    $instagram = substr($instagram, 1);
}

if (empty($nom) || empty($prenom) || empty($phone) || empty($email) || empty($password) || empty($instagram)) {
    setcookie("form_nom", $nom, time() + 60, "/");
    setcookie("form_prenom", $prenom, time() + 60, "/");
    setcookie("form_phone", $phone, time() + 60, "/");
    setcookie("form_email", $email, time() + 60, "/");
    setcookie("form_insta", $instagram, time() + 60, "/");
    header("Location: ../signup.php?error=Tous les champs sont obligatoires.");
    exit();
}


if (
    filter_var($instagram, FILTER_VALIDATE_EMAIL) ||
    !preg_match('/^(?=.*[A-Za-z])(?!.*\.\.)(?!.*\.$)(?!^\.)[A-Za-z0-9._]{1,30}$/', $instagram)
) {
    setcookie("form_nom", $nom, time() + 60, "/");
    setcookie("form_prenom", $prenom, time() + 60, "/");
    setcookie("form_phone", $phone, time() + 60, "/");
    setcookie("form_email", $email, time() + 60, "/");
    setcookie("form_insta", $instagram, time() + 60, "/");
    header("Location: ../signup.php?error=Identifiant Instagram invalide.");
    exit();
}

if (!preg_match("/^[a-zA-ZÀ-ÖØ-öø-ÿ\s'-]+$/u", $nom) || !preg_match("/^[a-zA-ZÀ-ÖØ-öø-ÿ\s'-]+$/u", $prenom)) {
    setcookie("form_nom", $nom, time() + 60, "/");
    setcookie("form_prenom", $prenom, time() + 60, "/");
    setcookie("form_phone", $phone, time() + 60, "/");
    setcookie("form_email", $email, time() + 60, "/");
    setcookie("form_insta", $instagram, time() + 60, "/");
    header("Location: ../signup.php?error=Nom ou prénom invalide (caractères interdits).");
    exit();
}

if (mb_strlen($nom, 'UTF-8') < 2 || mb_strlen($nom, 'UTF-8') > 50 ||
    mb_strlen($prenom, 'UTF-8') < 2 || mb_strlen($prenom, 'UTF-8') > 50) {
    setcookie("form_nom", $nom, time() + 60, "/");
    setcookie("form_prenom", $prenom, time() + 60, "/");
    setcookie("form_phone", $phone, time() + 60, "/");
    setcookie("form_email", $email, time() + 60, "/");
    setcookie("form_insta", $instagram, time() + 60, "/");
    header("Location: ../signup.php?error=Nom/Prénom doivent faire entre 2 et 50 caractères.");
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setcookie("form_nom", $nom, time() + 60, "/");
    setcookie("form_prenom", $prenom, time() + 60, "/");
    setcookie("form_phone", $phone, time() + 60, "/");
    setcookie("form_email", $email, time() + 60, "/");
    setcookie("form_insta", $instagram, time() + 60, "/");
    header("Location: ../signup.php?error=Email invalide.");
    exit();
}

if (!preg_match('/^0[67][0-9]{8}$/', $phone)) {
    setcookie("form_nom", $nom, time() + 60, "/");
    setcookie("form_prenom", $prenom, time() + 60, "/");
    setcookie("form_phone", $phone, time() + 60, "/");
    setcookie("form_email", $email, time() + 60, "/");
    setcookie("form_insta", $instagram, time() + 60, "/");
    header("Location: ../signup.php?error=Numéro de téléphone invalide.");
    exit();
}

$check = $pdo->prepare("SELECT 1 FROM user WHERE email = ?");
$check->execute([$email]);

if ($check->rowCount() > 0) {
    setcookie("form_nom", $nom, time() + 60, "/");
    setcookie("form_prenom", $prenom, time() + 60, "/");
    setcookie("form_phone", $phone, time() + 60, "/");
    setcookie("form_email", $email, time() + 60, "/");
    setcookie("form_insta", $instagram, time() + 60, "/");
    header("Location: ../signup.php?error=Un compte avec cet email existe déjà.");
    exit();
}


if (mb_strlen($password, 'UTF-8') < 8 ||
    !preg_match('/[A-Z]/', $password) ||
    !preg_match('/[a-z]/', $password) ||
    !preg_match('/[0-9]/', $password)) {

    setcookie("form_nom", $nom, time() + 60, "/");
    setcookie("form_prenom", $prenom, time() + 60, "/");
    setcookie("form_phone", $phone, time() + 60, "/");
    setcookie("form_email", $email, time() + 60, "/");
    setcookie("form_insta", $instagram, time() + 60, "/");
    header("Location: ../signup.php?error=Mot de passe trop faible (8 caractères minimum, avec majuscule, minuscule et chiffre).");
    exit();
}

$nom = mb_convert_case($nom, MB_CASE_TITLE, 'UTF-8');
$prenom = mb_convert_case($prenom, MB_CASE_TITLE, 'UTF-8');

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO user (nom, prenom, phone, email, password, instagram) VALUES (?, ?, ?, ?, ?, ?)");
$ok = $stmt->execute([$nom, $prenom, $phone, $email, $hashedPassword, $instagram]);

if ($ok) {
    header("Location: ../login.php?success=Inscription réussie, veuillez vous connecter.");
    exit();
} else {
    setcookie("form_nom", $nom, time() + 60, "/");
    setcookie("form_prenom", $prenom, time() + 60, "/");
    setcookie("form_phone", $phone, time() + 60, "/");
    setcookie("form_email", $email, time() + 60, "/");
    header("Location: ../signup.php?error=Erreur lors de l'inscription.");
    exit();
}
?>