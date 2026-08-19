<?php
session_start();
require_once "includes/global.php";
require_once "includes/isset_connection.php";
require_once "includes/bdd.php";

$stmt = $pdo->prepare("SELECT nom, prenom, email, phone, instagram FROM user WHERE id = :id");
$stmt->execute(['id' => $_SESSION['id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - <?= $webname ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/nav.css">
    <style>
        html,body {
            background-color: #4B4E53;
            background-image: linear-gradient(147deg, #4B4E53 0%, #000000 74%);
            min-height: 100vh;
            color: white;
        }
        div,p {
            font-size: 1.5rem;
        }
        .card {
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        .card.show {
            opacity: 1;
            transform: translateY(0);
        }
        .custom-btn {
            padding: 10px 20px;
            width: 100%;
            max-width: none;
            text-align: center;
            background-color: #ffffff20;
            color: white;
            border: 1px solid white;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s;
        }

        .custom-btn:hover {
            background-color: #ffffff40;
            transform: scale(1.05);
        }

        .custom-btn.logout {
            border-color: #ff5c5c;
            color: #ff5c5c;
        }

        .custom-btn.logout:hover {
            background-color: #ff5c5c20;
        }
        @media (max-width: 576px) {
            .card {
                padding: 1.5rem !important;
            }
        }
    </style>
</head>
<body>
<?php require_once "includes/nav.php"; ?>
<div class="container mb-5 mt-3">

    <h1 style="margin: auto; width: 100%;">Profil</h1>
    <div class="row mt-5">
        <div class="col-12 col-md-6 mx-auto my-2">
            <div class="card" style="background-color: rgba(255, 255, 255, 0.1); color: white; padding: 3rem; text-align: center;">
                <?php require_once 'includes/if_message.php'?>
                <div class="card-body">
                    <p><strong>Prénom :</strong><br><?= htmlspecialchars(ucfirst(strtolower($user['prenom']))) ?></p>
                    <p><strong>Nom :</strong><br><?= htmlspecialchars(strtoupper($user['nom'])) ?></p>
                    <p><strong>Instagram :</strong><br>@<?= htmlspecialchars(strtolower($user['instagram'])) ?></p>
                    <p><strong>Email :</strong><br><?= htmlspecialchars($user['email']) ?></p>
                    <p><strong>Téléphone :</strong><br><?= htmlspecialchars($user['phone']) ?></p>
                    <div class="mt-4 d-grid gap-3" style="grid-template-columns: 1fr;">
                        <a href="profil_edit.php" class="custom-btn">Éditer le profil</a>
                        <a href="traitements/deconnexion.php" class="custom-btn logout">Se déconnecter</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once "includes/footer2.php"; ?>
</body>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const cards = document.querySelectorAll(".card");
    cards.forEach((card, i) => {
        setTimeout(() => card.classList.add("show"), 200 * i);
    });
});
</script>
</html>