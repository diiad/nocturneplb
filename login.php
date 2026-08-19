<?php
session_start();
require_once "includes/global.php";
if(isset($_SESSION["email"]) || isset($_SESSION["pseudo"])){
header("Location: dashboard.php");
exit();
}
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se connecter - <?= $webname ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<style>
    .logo{
       height: 10rem;
    }
    html,body{
        background-color: #4B4E53;
        background-image: linear-gradient(147deg, #4B4E53 0%, #000000 74%);
        min-height: 100vh;

    }
    .container {
        height: 90vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        max-width: 100%;
        padding: 0 1rem;
    }
    .button1{
        background: linear-gradient(135deg, #444, #666);
        color: white;
        border-radius: 30px;
        border: none;
        width: auto;
        padding: 1.5rem 3rem;
        box-shadow: rgba(0, 0, 0, 0.3) 0px 19px 38px, rgba(0, 0, 0, 0.22) 0px 15px 12px;
        font-size: 2rem;
    }
    @media (max-width: 600px) {
        .button1 {
            font-size: 1.2rem;
            padding: 1rem 2rem;
            width: 100%;
        }
        .container {
            height: 85vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            max-width: 100%;
            padding: 0 1rem;
        }

    }
    .button1:hover {
        filter: brightness(85%);
        cursor: pointer;
    }
    .row {
        width: 100%;
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
    }
    .row button {
        margin: 0 auto;
    }

    .formulaire {
        position: relative;
        text-align: center;
        background: rgba(0, 0, 0, 0.35);
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.25);
        color: white;
        max-width: 30rem;
        width: 90%;
        margin: 1rem auto;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    input[type="email"], input[type="password"] {
        width: 100%;
        padding: 0.75rem;
        margin-bottom: 1rem;
        border-radius: 8px;
        border: none;
    }

    .btn-login {
        background-color: #6c757d;
        border: none;
        border-radius: 25px;
        color: white;
        padding: 0.75rem 2rem;
        font-size: 1rem;
        transition: background-color 0.3s;
    }

    .btn-login:hover {
        background-color: #5a6268;
        cursor: pointer;
    }

    .Basdepage a {
        display: block;
        margin-top: 0.5rem;
        text-decoration: none;
        color: white;
    }
    form a:hover {
        text-decoration: underline;
    }
    label{
        margin-bottom: 1rem;
    }
</style>
    </head>
<body>
<div class="container">
    <img src="img/NOC_O_blanc.png" alt="logo blanc de la nocturne" class="logo mt-5">
    <div class="formulaire main">

        <?php require_once 'includes/if_message.php'?>
    <h1>Se connecter</h1>



    <form action="traitements/login_verif.php" method="POST">
        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" name="email" id="email" placeholder="Email"
                   value="<?= isset($_COOKIE['email']) ? htmlspecialchars($_COOKIE['email']) : '' ?>">

            <label for="password">Mot de passe :</label>
            <input type="password" name="password" id="password" placeholder="Mot de passe" >
            <div class="Basdepage py-3">
                <button class="btn-login mb-2" type="submit">Se connecter</button>
                <a href="password_forgot.php">Mot de passe oublié ?</a>
                <a href="signup.php" class="text-light">S'inscrire ici</a>
            </div>
        </div>
</div>
    </form>
</div>
<div>
        <?php require_once "includes/footer.php"; ?>
</div>
</body>