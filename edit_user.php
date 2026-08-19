<?php
session_start();
require_once "includes/global.php";
require_once "includes/isset_connection.php";
require_once "includes/bdd.php";
require_once "includes/only_admin.php";

$stmt = $pdo->prepare("SELECT id, nom, prenom, email, phone, commentaire, instagram FROM user WHERE id = :id");
$stmt->execute(['id' => $_GET['id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éditer Profil - <?= $webname ?></title>
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
            margin: auto;
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
    </style>
</head>
<body>
<?php require_once "includes/nav.php"; ?>
<div class="container my-5">
    <h1 style="margin: auto; width: 100%;">Éditer le profil</h1>
    <div class="row mt-5">

        <div class="col-12 col-md-6 mx-auto my-2">
            <div class="card" style="background-color: rgba(255, 255, 255, 0.1); color: white; padding: 3rem; text-align: center;">
                <div class="card-body">
                    <?php require_once 'includes/if_message.php'; ?>
                    <form action="traitements/update_user.php" method="post">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($_GET['id']) ?>">
                        <div class="mb-3 text-start">
                            <label for="prenom" class="form-label"><strong>Prénom :</strong></label>
                            <input type="text" class="form-control" id="prenom" name="prenom" value="<?= htmlspecialchars(ucfirst(strtolower($user['prenom']))) ?>" required readonly>
                        </div>
                        <div class="mb-3 text-start">
                            <label for="nom" class="form-label"><strong>Nom :</strong></label>
                            <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars(strtoupper($user['nom'])) ?>" required readonly>
                        </div>
                        <div class="mb-3 text-start">
                            <label for="email" class="form-label"><strong>Email :</strong></label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>
                        <div class="mb-3 text-start">
                            <label for="phone" class="form-label"><strong>Téléphone :</strong></label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
                        </div>
                        <div class="mb-3 text-start">
                            <label for="instagram" class="form-label"><strong>Instagram :</strong></label>
                            <input type="text"
                                   class="form-control"
                                   id="instagram"
                                   name="instagram"
                                   value="<?= htmlspecialchars($user['instagram'] ?? '') ?>">
                        </div>
                        <div class="mb-3 text-start">
                            <label for="commentaire" class="form-label"><strong>Commentaire :</strong></label>
                            <textarea
                                    class="form-control"
                                    id="commentaire"
                                    name="commentaire"
                                    rows="3"
                                    style="resize: vertical;"><?= htmlspecialchars($user['commentaire'] ?? '') ?></textarea>
                        </div>
                        <div class="mt-4 d-grid gap-3" style="grid-template-columns: 1fr 1fr;">
                            <button type="submit" class="custom-btn">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <h1 class="mt-5">Ajouter un paiement</h1>
        <div class="col-12 col-md-6 mx-auto my-4">
            <div class="card" style="background-color: rgba(255, 255, 255, 0.1); color: white; padding: 3rem; text-align: center;">
                <div class="card-body">
                    <form action="traitements/paiement_verif.php" method="post">
                        <?php require_once "includes/if_message.php"; ?>
                        <div class="mb-3 text-start">
                            <label for="search" class="form-label"><strong>Adhérent :</strong></label>
                            <input type="text" class="form-control form-control-lg" name="search" id="search" value="<?= htmlspecialchars($user['phone']) ?>" readonly>
                        </div>
                        <div class="mb-3 text-start">
                            <label for="montant" class="form-label"><strong>Montant :</strong></label>
                            <input type="text" class="form-control form-control-lg" name="montant" id="montant" placeholder="Montant" required>
                        </div>
                        <div class="mb-3 text-start">
                            <label for="saison_id" class="form-label"><strong>Saison :</strong></label>
                            <select name="saison_id" id="saison_id" class="form-select form-select-lg" required>
                                <option value="">Choisir une saison</option>
                                <?php
                                $query = $pdo->query("SELECT id, CONCAT(annee_debut, '-', annee_fin) AS nom_saison FROM saison ORDER BY annee_debut DESC");
                                while ($row = $query->fetch()) {
                                    echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['nom_saison']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mt-4 d-flex flex-column gap-3">
                            <button type="submit" class="custom-btn w-100" style="padding: 1.5rem;">Enregistrer</button>

                            <a href="show_paiement.php?user_id=<?= urlencode($user['id']) ?>"
                               class="custom-btn w-100"
                               style="padding: 1.5rem; text-align: center;">
                                Voir tous les paiements
                            </a>
                        </div>
                    </form>
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