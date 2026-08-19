<?php
session_start();
require_once "includes/global.php";
require_once "includes/isset_connection.php";
require_once "includes/bdd.php";
require_once "includes/only_admin.php";

?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des saisons - <?= $webname ?></title>
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
            font-size: 2rem;
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
        .custom-btn.logout {
            border-color: #ff5c5c;
            color: #ff5c5c;
        }

        .custom-btn.logout:hover {
            background-color: #ff5c5c20;
        }
        .custom-btn:hover {
            background-color: #ffffff40;
            transform: scale(1.05) !important;
        }

        @media (max-width: 576px) {
            div, p {
                font-size: 1.2rem;
            }

            .card {
                padding: 1rem !important;
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
        }
    </style>
</head>
<body>
<?php require_once "includes/nav.php";?>
<div class="container my-5">
    <h1 class="text-center">Modifier une saison</h1>

    <form method="get" class="mb-4">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <label for="saison" class="form-label">Sélectionnez une saison</label>
                <select class="form-select" id="saison" name="saison" onchange="this.form.submit()">
                    <?php
                    $saisons = $pdo->query("SELECT id, annee_debut, annee_fin FROM saison ORDER BY annee_debut DESC");
                    $selectedId = isset($_GET['saison']) ? $_GET['saison'] : null;
                    while ($s = $saisons->fetch()) {
                        $selected = $selectedId == $s['id'] ? 'selected' : '';
                        echo '<option value="'.$s['id'].'" '.$selected.'>'.$s['annee_debut'].'-'.$s['annee_fin'].'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </form>

    <div class="row mt-5 align-items-stretch h-100">
        <div class="col-12 col-md-6 mx-auto my-2 h-100">
            <div class="card h-100 d-flex flex-column justify-content-center" style="background-color: rgba(255, 255, 255, 0.1); color: white; padding: 4rem !important; text-align: center; font-size: 1rem">
                <div class="card-body">
                    <form class="my-4">
                    <?php
                    if ($selectedId) {
                        $stmt = $pdo->prepare("SELECT * FROM saison WHERE id = ?");
                        $stmt->execute([$selectedId]);
                        $saisonInfo = $stmt->fetch();
                        if ($saisonInfo) {
                            echo '<p class="card-text">Saison :</p>';
                            echo '<p class="card-text">'.htmlspecialchars($saisonInfo['annee_debut']).' - '.htmlspecialchars($saisonInfo['annee_fin']).'</p>';
                            echo '<p class="card-text">Prix :</p>';
                            echo '<input type="number" step="0.01" name="prix" value="'.htmlspecialchars($saisonInfo['prix']).'" class="form-control mb-3" style="max-width: 300px; margin: 0 auto; height: 4rem;">';
                            $isChecked = $saisonInfo['active'] ? 'checked' : '';
                            echo '<div class="form-check">';
                            echo '<input class="form-check-input" type="checkbox" name="active" id="active" '.$isChecked.'>';
                            echo '<label class="form-check-label" for="active">Saison active</label>';
                            echo '</div>';
                        } else {
                            echo '<p class="card-text text-warning">Saison introuvable.</p>';
                        }
                    } else {
                        echo '<p class="card-text text-warning">Aucune saison sélectionnée.</p>';
                    }
                    ?>
<br>
                    <?php if ($selectedId && $saisonInfo): ?>
                        <a href="#" class="custom-btn">Sauvegarder</a>
                        </form>
                        <a href="traitements/delete_season.php?id=<?= $_GET['saison'] ?>" class="custom-btn logout">Supprimer la saison</a>
                    <?php endif; ?>


                </div>
            </div>
        </div>


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
