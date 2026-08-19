<?php
session_start();
require_once "includes/global.php";
if(isset($_SESSION["email"])){
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - <?= $webname ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>

        html,body{
            background-color: #4B4E53;
            background-image: linear-gradient(147deg, #4B4E53 0%, #000000 74%);
            min-height: 100vh;
        }
        .container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        .formulaire {
            position: relative;
            text-align: center;
            background: rgba(0, 0, 0, 0.35);
            padding: 2rem;
            padding-bottom: 3rem;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.25);
            color: white;
            max-width: 500px;
            width: 100%;
            box-sizing: border-box;
            margin: 1rem auto;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .form-group {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: stretch;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border-radius: 8px;
            border: none;
        }
        .btn-signup {
            background-color: #6c757d;
            border: none;
            border-radius: 25px;
            color: white;
            padding: 0.75rem 2rem;
            font-size: 1rem;
            transition: background-color 0.3s;
            width: 100%;
        }
        .btn-signup:hover {
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

        @media (max-width: 576px) {
            .formulaire {
                padding: 1rem;
                padding-bottom: 2rem;
                border-radius: 0.5rem;
            }

            input[type="text"],
            input[type="email"],
            input[type="password"] {
                padding: 0.5rem;
            }

            .btn-signup {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="formulaire main">
        <?php require_once 'includes/if_message.php'?>
        <h1>S'inscrire</h1>
        <form action="traitements/signup_verif.php" id="signupForm" method="POST">
            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" name="nom" id="nom" placeholder="Nom" required value="<?= isset($_COOKIE['form_nom']) ? htmlspecialchars($_COOKIE['form_nom']) : '' ?>">

                <label for="prenom">Prenom :</label>
                <input type="text" name="prenom" id="prenom" placeholder="prenom" required value="<?= isset($_COOKIE['form_prenom']) ? htmlspecialchars($_COOKIE['form_prenom']) : '' ?>">

                <label for="phone">Numéro de téléphone :</label>
                <input type="text" name="phone" id="phone" placeholder="Numéro de téléphone" required value="<?= isset($_COOKIE['form_phone']) ? htmlspecialchars($_COOKIE['form_phone']) : '' ?>">

                <label for="instagram">Instagram :</label>
                <input type="text" name="instagram" id="instagram" placeholder="Instagram" required value="<?= isset($_COOKIE['form_insta']) ? htmlspecialchars($_COOKIE['form_insta']) : '' ?>">


                <label for="email">Email :</label>
                <input type="email" name="email" id="email" placeholder="Email" required value="<?= isset($_COOKIE['form_email']) ? htmlspecialchars($_COOKIE['form_email']) : '' ?>">

                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password" placeholder="Mot de passe" class="toggle" required>

                <label for="confirm_password">Confirmer le mot de passe :</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirmer le mot de passe" class="toggle" required>
                <span class="my-1">Afficher le mot de passe</span><br><input type="checkbox" onclick="TogglePassword()" style="height: 2rem;" class="mx-auto">

                <div class="Basdepage py-3">
                    <button class="btn-signup mb-2" type="submit">S'inscrire</button>
                    <a href="login.php" class="text-light">Déjà un compte ? Se connecter</a>
                </div>
            </div>
        </form>
    </div>
</div>
<div>
    <?php require_once "includes/footer.php"; ?>
</div>
<script>
    function TogglePassword() {
        var fields = document.querySelectorAll(".toggle");
        fields.forEach(function(field) {
            if (field.type === "password") {
                field.type = "text";
            } else if (field.type === "text") {
                field.type = "password";
            }
        });
    }
</script>
</body>