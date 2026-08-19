<?php
session_start();
require_once "includes/global.php";
require_once "includes/isset_connection.php";
require_once "includes/bdd.php";
require_once "includes/only_admin.php";

$heure = date("H");
$salutation = ($heure >= 6 && $heure < 18) ? "Bonjour" : "Bonsoir";

$defaultSaisonId = null;
$result = $pdo->query("SELECT id FROM saison WHERE active = 1 LIMIT 1");
if ($row = $result->fetch()) {
    $defaultSaisonId = $row['id'];
}

$saisons = $pdo->query("SELECT id, annee_debut, annee_fin FROM saison ORDER BY annee_debut DESC");

$saisonId = isset($_GET['saison']) ? $_GET['saison'] : $defaultSaisonId;

$stmt = $pdo->prepare("SELECT annee_debut, annee_fin FROM saison WHERE id = ?");
$stmt->execute([$saisonId]);
$saisonInfo = $stmt->fetch();

$stmt = $pdo->prepare("SELECT COUNT(DISTINCT user_id) FROM paiement WHERE saison_id = ?");
$stmt->execute([$saisonId]);
$nbAdherents = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT SUM(montant) FROM paiement WHERE saison_id = ?");
$stmt->execute([$saisonId]);
$montantCollecte = $stmt->fetchColumn() ?? 0;

$stmt = $pdo->prepare("SELECT prix FROM saison WHERE id = ?");
$stmt->execute([$saisonId]);
$prix = $stmt->fetchColumn();
$prix *= $nbAdherents;
$montantrecuperable = $prix - $montantCollecte;

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
        a{
            text-decoration: none;
            color: white;
        }
        a:hover{
            text-decoration: underline;
        }

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

        .custom-btn {
            padding: 15px 30px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            background-color: #ffffff20;
            color: white;
            border: 1px solid white;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s;
            font-size: 1.25rem;
        }

        .custom-btn {
            font-size: 1rem;
            padding: 12px 24px;
            display: block;
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            box-sizing: border-box;
        }

        .custom-btn:hover {
            background-color: #ffffff40;
            transform: scale(1.05);
        }

        .manage{
            text-decoration: none !important;
        }
    </style>
</head>
<body>
<?php require_once "includes/nav.php";?>
<div class="container my-5">
    <form method="get" class="mb-4">
        <div class="row">
            <div class="col-md-8">
                <label for="saison" class="form-label">Sélectionnez une saison</label>
                <select class="form-select" id="saison" name="saison" onchange="this.form.submit()">
                    <?php
                    while ($s = $saisons->fetch()) {
                        $selected = (isset($_GET['saison']) ? $_GET['saison'] : $defaultSaisonId) == $s['id'] ? 'selected' : '';
                        echo '<option value="'.$s['id'].'" '.$selected.'>'.$s['annee_debut'].'-'.$s['annee_fin'].'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </form>
    <h1>Statistique pour la saison
        <?php
        if ($saisonId) {
            if ($saisonInfo) {
                echo htmlspecialchars($saisonInfo['annee_debut']) . '-' . htmlspecialchars($saisonInfo['annee_fin']);
            } else {
                echo "Inconnue";
            }
        } else {
            echo "Non sélectionnée";
        }
        ?>
    </h1>

    <div class="row mt-5 align-items-stretch h-100">
        <?php require_once 'includes/if_message.php'?>

        <div class="col-12 col-md-6 mx-auto my-2 h-100">
            <div class="card h-100 d-flex flex-column justify-content-center" style="background-color: rgba(255, 255, 255, 0.1); color: white; padding: 4rem; text-align: center; font-size: 1rem">
                <div class="card-body">
                    <?php
                    echo '<p class="card-text"><img class="emoji-inline" src="img/family-man-man-boy_1f468-200d-1f468-200d-1f466.png">Il y a ' . $nbAdherents . ' adhérent' . ($nbAdherents > 1 ? 's' : '') . ' inscrit' . ($nbAdherents > 1 ? 's' : '') . '.</p>';
                    ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 mx-auto my-2 h-100">
            <div class="card h-100 d-flex flex-column justify-content-center" style="background-color: rgba(255, 255, 255, 0.1); color: white; padding: 4rem; text-align: center; font-size: 1rem">
                <div class="card-body">
                    <?php
                    echo '<p class="card-text"><img class="emoji-inline" src="img/bank_1f3e6.png"> Il y a <span class="animated-number" data-target="' . htmlspecialchars($montantCollecte, ENT_QUOTES, 'UTF-8') . '">0</span> € de collecté</p>';
                    ?>
                </div>
            </div>
        </div>
        <?php if($montantrecuperable > 0){ ?>
            <div class="col-12 col-md-6 mx-auto my-2 h-100">
                <div class="card h-100 d-flex flex-column justify-content-center" style="background-color: rgba(255, 255, 255, 0.1); color: white; padding: 4rem; text-align: center; font-size: 1rem">
                    <div class="card-body">
                        <?php
                        echo '<p class="card-text"> <img class="emoji-inline" src="img/money-with-wings_1f4b8.png">Il y a <span class="animated-number" data-target="' . htmlspecialchars($montantrecuperable, ENT_QUOTES, 'UTF-8') . '">0</span> € potentiels à récupérer.</p>';
                        echo '<a href="missing_payment.php?saison=' . urlencode($saisonId) . '" class="text-white">Voir les paiements manquants</a>';
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="col-12 col-md-6 mx-auto my-2 h-100">
            <div class="card h-100 d-flex flex-column justify-content-center" style="background-color: rgba(255, 255, 255, 0.1); color: white; padding: 4rem; text-align: center; font-size: 1rem">
                <div class="card-body">
                    <p class="card-text">Créer une nouvelle saison :</p>
                    <form action="traitements/add_saison.php" method="post">
                        <div class="mb-3">
                            <label for="annee_debut" class="form-label">Année de début</label>
                            <input type="text" class="form-control" id="annee_debut" name="annee_debut" maxlength="4" required>
                        </div>
                        <div class="mb-3">
                            <label for="annee_fin" class="form-label">Année de fin</label>
                            <input type="text" class="form-control" id="annee_fin" name="annee_fin" maxlength="4" required>
                        </div>
                        <div class="mb-3">
                            <label for="prix" class="form-label">Prix de la saison (€)</label>
                            <input type="number" step="0.01" class="form-control" id="prix" name="prix" required>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="1" id="active" name="active" checked>
                            <label class="form-check-label" for="active">
                                Saison active
                            </label>
                        </div>
                        <button type="submit" class="custom-btn">Créer la saison</button>
                    </form>
                    <a href="manage_season.php?saison=<?= urlencode($saisonId) ?>" class="custom-btn mt-4 w-100 manage">Gérer les saisons</a>
                </div>
            </div>
        </div>

    </div>

</div>
<?php require_once "includes/footer2.php";?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Animation pour les cards
        const cards = document.querySelectorAll(".card");
        cards.forEach((card, i) => {
            setTimeout(() => card.classList.add("show"), 200 * i);
        });

        function animateNumber(el) {
            const target = parseFloat(el.getAttribute("data-target").replace(",", "."));
            let current = 0;
            const duration = 2500;
            const startTime = performance.now();
            function update(now) {
                const elapsed = now - startTime;
                const progress = Math.min(elapsed / duration, 1);
                let value = target * progress;

                el.textContent = value.toLocaleString('fr-FR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                if (progress < 1) {
                    requestAnimationFrame(update);
                } else {
                    el.textContent = target.toLocaleString('fr-FR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                }
            }
            requestAnimationFrame(update);
        }
        document.querySelectorAll('.animated-number').forEach(animateNumber);
    });
</script>
</body>
</html>
