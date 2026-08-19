<?php
session_start();
require_once "includes/global.php";
require_once "includes/isset_connection.php";
require_once "includes/bdd.php";
require_once "includes/only_admin.php";

if (isset($_GET['saison'])) {
    $saisonId = (int) $_GET['saison'];
} else {
    $stmt = $pdo->query("SELECT id FROM saison WHERE active = 1 LIMIT 1");
    $saison = $stmt->fetch(PDO::FETCH_ASSOC);
    $saisonId = $saison['id'];
}
if ($saisonId) {
    $stmt = $pdo->prepare("
        SELECT 
            u.prenom, 
            u.nom,
            u.instagram AS insta, 
            u.phone AS telephone, 
            u.email AS mail, 
            s.prix - IFNULL(SUM(p.montant), 0) AS reste_a_payer
        FROM user u
        CROSS JOIN saison s
        LEFT JOIN paiement p ON p.user_id = u.id AND p.saison_id = s.id
        WHERE s.id = :saison_id
        GROUP BY u.id, s.id, s.prix
        HAVING reste_a_payer > 0 AND SUM(p.montant) > 0
        ORDER BY reste_a_payer DESC
    ");
    $stmt->execute(['saison_id' => $saisonId]);
    $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $utilisateurs = [];
}
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
        a{
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
    <h1>Paiements manquants</h1>

    <div class="row mt-5 align-items-stretch h-100">
        <?php foreach ($utilisateurs as $u): ?>
            <div class="col-12 col-md-6 mx-auto my-2 h-100">
                <div class="card h-100 d-flex flex-column justify-content-center mb-3" style="background-color: rgba(255, 255, 255, 0.1); color: white; padding: 2rem; text-align: center; font-size: 1rem">
                    <div class="card-body">
                        <p class="card-text">
                            <img class="emoji-inline" src="img/money-with-wings_1f4b8.png">
                            <?= htmlspecialchars(ucfirst(strtolower($u['prenom'])) . ' ' . strtoupper($u['nom'])) ?><br>
                            <a target="_blank" href="https://www.instagram.com/<?=htmlspecialchars($u['insta'])?>" ><?= "@". htmlspecialchars($u['insta']) ?></a><br>
                            <?= htmlspecialchars($u['telephone']) ?><br>
                            <?= htmlspecialchars($u['mail']) ?><br>
                            <?php if ((float)$u['reste_a_payer'] <= 0): ?>
                                <span class="text-success">Tout est payé </span>
                            <?php else: ?>
                                <span class="text-warning">Il reste : <?= number_format($u['reste_a_payer'], 2, ',', ' ') ?> €</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>
<?php require_once "includes/footer2.php";?>
</body>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const cards = document.querySelectorAll(".card");
        cards.forEach((card, i) => {
            setTimeout(() => card.classList.add("show"), 5 * i);
        });
    });
</script>
</html>
