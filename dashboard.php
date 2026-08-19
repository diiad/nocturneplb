<?php
session_start();
require_once "includes/global.php";
require_once "includes/isset_connection.php";
require_once "includes/bdd.php";
$stmt = $pdo->prepare("
    SELECT SUM(p.montant) AS total_paye
    FROM paiement p
    JOIN saison s ON p.saison_id = s.id
    WHERE s.active = 1 AND p.user_id = :user_id
");

$stmt->execute(['user_id' => $_SESSION['id']]);
$cotisation = $stmt->fetchColumn() ?? 0;
$heure = date("H");
$salutation = ($heure >= 6 && $heure < 18) ? "Bonjour" : "Bonsoir";

if($_SESSION['role'] == 1){
    $salutation = ($heure >= 6 && $heure < 18) ? "Bonjour ADMIN" : "Bonsoir ADMIN";
}

$stmt = $pdo->query("SELECT annee_debut, annee_fin FROM saison WHERE active = 1 LIMIT 1");
$saison = $stmt->fetch(PDO::FETCH_ASSOC);
$debut = $saison['annee_debut'];
$fin = $saison['annee_fin'];

$prix_saison = $pdo->query("SELECT prix FROM saison WHERE active = 1 LIMIT 1")->fetchColumn();
$difference = $prix_saison - $cotisation;
if ($difference < 0) $difference = 0;
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - <?= $webname ?></title>
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
        /*==============================================================================================================
        =============================================================================================================*/
        div,p{
            font-size: 1.5rem;
        }
        .card {
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
            height: 100%;
        }

        .card.show {
            opacity: 1;
            transform: translateY(0);
        }

        .emoji-inline {
            height: 1em;
            vertical-align: middle;
            position: relative;
            top: -0.1em;
            margin-right: 0.4em;
        }

    </style>
</head>
<body>
<?php require_once "includes/nav.php";?>
<div class="container my-5">
<h1><?= $salutation ?> <?= ucfirst(strtolower($_SESSION['prenom'])) ?>, </h1>

<div class="row mt-5 align-items-stretch h-100">
    <div class="col-12 col-md-6 mx-auto my-2 h-100">
        <div class="card h-100 d-flex flex-column justify-content-center" style="background-color: rgba(255, 255, 255, 0.1); color: white; padding: 4rem; text-align: center; font-size: 1rem">
            <div class="card-body">
                <p class="card-text"><img class="emoji-inline" src="img/money-with-wings_1f4b8.png"> Vous avez réglé <?= $cotisation ?> € pour la saison <?= $debut ?>/<?= $fin ?>.</p>
            </div>
        </div>
    </div>

    <?php if ($difference > 0) { ?>
        <div class="col-12 col-md-6 mx-auto my-2 h-100">
            <div class="card h-100 d-flex flex-column justify-content-center" style="background-color: rgba(255, 255, 255, 0.1); color: white; padding: 4rem; text-align: center; font-size: 1rem">
                <div class="card-body">
                    <p class="card-text"><img class="emoji-inline" src="img/cross-mark_274c.png"> Vous n’avez pas encore réglé l’intégralité de votre cotisation annuelle pour être un(e) adhérent(e) du Paris Lady Basket</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 mx-auto my-2 h-100">
            <div class="card h-100 d-flex flex-column justify-content-center" style="background-color: rgba(255, 255, 255, 0.1); color: white; padding: 4rem; text-align: center; font-size: 1rem">
                <div class="card-body">
                    <p class="card-text"><img class="emoji-inline" src="img/bank_1f3e6.png"> Il vous reste encore <?= $difference ?> € à régler.</p>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="col-12 col-md-6 mx-auto my-2 h-100">
            <div class="card h-100 d-flex flex-column justify-content-center" style="background-color: rgba(255, 255, 255, 0.1); color: white; padding: 4rem; text-align: center; font-size: 1rem">
                <div class="card-body">
                    <p class="card-text"><img class="emoji-inline" src="img/check-mark-button_2705.png"> Vous avez réglé la totalité de votre cotisation. Vous etes un(e) adhérent(e) du Paris Lady Basket</p>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

</div>
<?php require_once "includes/footer2.php";?>
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
