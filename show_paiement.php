<?php
session_start();
require_once "includes/global.php";
require_once "includes/isset_connection.php";
require_once "includes/bdd.php";
require_once "includes/only_admin.php";

if (!isset($_GET['user_id']) || !ctype_digit($_GET['user_id'])) {
    die('Utilisateur invalide');
}

$userId = (int) $_GET['user_id'];

$stmt = $pdo->prepare('SELECT id, prenom, nom, phone FROM user WHERE id = ?');
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('Utilisateur introuvable');
}

$stmt = $pdo->prepare('SELECT id, montant, date FROM paiement WHERE user_id = ? ORDER BY date DESC');
$stmt->execute([$userId]);
$paiements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Paiements – <?= htmlspecialchars($user['prenom'].' '.$user['nom']) ?></title>
    <style>
        html, body {
            background-color: #4B4E53;
            background-image: linear-gradient(147deg, #4B4E53 0%, #000000 74%);
            min-height: 100vh;
            color: white;
        }

        a {
            color: white;
        }

        body {
            font-size: 1rem;
        }

        .card p {
            font-size: 1.1rem;
            margin-bottom: .5rem;
        }
        .custom-btn {
            display: inline-block;
            width: fit-content;
            padding: 1rem 1.25rem;
            border-radius: .75rem;
            border: 1px solid rgba(255,255,255,0.25);
            background: rgba(255,255,255,0.10);
            color: white;
            text-decoration: none;
            transition: background .2s ease, transform .2s ease;
        }

        .custom-btn:hover {
            background: rgba(255,255,255,0.18);
            transform: translateY(-1px);
            color: white;
            text-decoration: none;
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

        nav, ul {
            list-style: none;
            text-decoration: none;
            background: rgba(0, 0, 0, 0.8);
            width: 100%;
            display: flex;
            flex-direction: row;
            align-items: baseline;
            justify-content: space-around;
            padding: 0;
            margin: 0;
            text-align: center;
        }

        nav {
            padding: 0;
            background: rgba(0, 0, 0, 0.85);
        }

        nav li {
            background: transparent;
            width: 70%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-bottom: 2px solid transparent;
            transition: border-bottom 0.3s ease;
            padding: 1rem;
        }

        nav li:hover {
            border-bottom: 2px solid white;
        }

        nav ul {
            background: transparent;
        }

        .position-footer {
            position: fixed;
            z-index: 999;
        }

        @media screen and (max-width: 768px) {
            nav {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                z-index: 999;
                border-radius: 1rem;
                overflow: hidden;
                margin-left: auto;
                margin-right: auto;
                margin-bottom: 1rem;
                width: 95%;
            }

            nav ul {
                flex-direction: row;
                justify-content: space-around;
                border-radius: 1rem;
            }

            nav li {
                width: auto;
                padding: 1rem;
            }

            body {
                padding-bottom: 70px;
            }

            .position-footer {
                top: 1rem;
                right: 1rem;
                bottom: auto;
                left: auto;
            }
        }

        @media screen and (min-width: 769px) {
            .position-footer {
                bottom: 1rem;
                left: 1rem;
                top: auto;
                right: auto;
            }
        }
    </style>
</head>
<body>
<?php include_once "includes/nav.php"; ?>

<div class="container my-5">

    <h1 class="mb-4">Paiements de <?= htmlspecialchars($user['prenom'].' '.$user['nom']) ?></h1>

    <?php if (empty($paiements)): ?>
        <div class="card" style="background-color: rgba(255,255,255,0.1); color:white; padding:2rem; text-align:center;">
            <p>Aucun paiement enregistré.</p>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($paiements as $p): ?>
                <div class="col-12 col-md-6 mx-auto my-2">
                    <div class="card h-100 position-relative" style="background-color: rgba(255,255,255,0.1); color:white; padding:2rem;">

                        <form method="POST"
                              action="delete_paiement.php"
                              onsubmit="return confirm('Confirmer la suppression de ce paiement ?');"
                              style="position:absolute; top:1rem; right:1rem;">

                            <input type="hidden" name="paiement_id" value="<?= (int)$p['id'] ?>">
                            <input type="hidden" name="user_id" value="<?= (int)$user['id'] ?>">

                            <button type="submit" class="btn btn-sm btn-outline-light">✕</button>
                        </form>

                        <div class="card-body">
                            <p><strong>Montant :</strong> <?= number_format($p['montant'], 2, ',', ' ') ?> €</p>
                            <p><strong>Date :</strong> <?= htmlspecialchars($p['date']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="mt-4">
        <a href="edit_user.php?id=<?= $user['id'] ?>" class="custom-btn">Retour au profil</a>
    </div>

</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, i) => {
            setTimeout(() => card.classList.add('show'), 150 * i);
        });
    });
</script>
</body>
</html>
