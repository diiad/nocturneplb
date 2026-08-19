<?php

session_start();
require_once "includes/global.php";
require_once "includes/isset_connection.php";
require_once "includes/bdd.php";
require_once "includes/only_admin.php";

// Récupérer l'ID de la saison active
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
    u.id,
    u.prenom, 
    u.nom,
    u.instagram AS insta, 
    u.phone AS telephone, 
    u.email AS mail, 
    s.prix AS total_a_payer,
    IFNULL(SUM(p.montant), 0) AS total_paye,
    s.prix - IFNULL(SUM(p.montant), 0) AS reste_a_payer
    FROM user u
    CROSS JOIN saison s
    LEFT JOIN paiement p ON p.user_id = u.id AND p.saison_id = s.id
    WHERE s.id = :saison_id
    GROUP BY u.id, s.id, s.prix
    ORDER BY reste_a_payer ASC, u.nom ASC;
    ");
    $stmt->execute(['saison_id' => $saisonId]);
    $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $utilisateurs = [];
}
$stmt = $pdo->prepare("SELECT COUNT(id) AS total_users FROM user");
$stmt->execute();
$total = (int) $stmt->fetch(PDO::FETCH_ASSOC)['total_users'] + 450;
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
    <h1 class="mb-3">Liste des utilisateurs (<span id="totalCount"><?= $total ?></span>)</h1>

    <div class="card mb-4" style="background-color: rgba(255, 255, 255, 0.08); color: white; padding: 1.25rem; border: 1px solid rgba(255,255,255,0.12)">
        <div class="row g-3 align-items-end">
            <div class="col-12 col-md-6">
                <label for="searchInput" class="form-label">Recherche</label>
                <input id="searchInput" type="text" class="form-control" placeholder="Nom, prénom, insta, email, téléphone…">
            </div>

            <div class="col-12 col-md-3">
                <label for="statusFilter" class="form-label">Statut paiement</label>
                <select id="statusFilter" class="form-select">
                    <option value="all" selected>Tous</option>
                    <option value="due">Reste à payer</option>
                    <option value="paid">Payé</option>
                </select>
            </div>

            <div class="col-12 col-md-3">
                <label for="amountFilter" class="form-label">Reste (max)</label>
                <input id="amountFilter" type="number" step="0.01" min="0" class="form-control" placeholder="ex: 50">
            </div>

            <div class="col-12 d-flex gap-2 flex-wrap">
                <div class="small" style="opacity: .85">
                    Derniers inscrits : <strong><span id="visibleCount">...</span></strong>
                </div>
                <button id="resetFilters" type="button" class="btn btn-outline-light btn-sm">Réinitialiser</button>
            </div>
        </div>
    </div>

    <div class="row mt-5 align-items-stretch h-100">
        <?php foreach ($utilisateurs as $u): ?>
            <div class="col-12 col-md-6 mx-auto my-2 h-100">
                <div class="card user-card h-100 d-flex flex-column justify-content-center mb-3"
                     data-name="<?= htmlspecialchars(mb_strtolower($u['prenom'] . ' ' . $u['nom'])) ?>"
                     data-insta="<?= htmlspecialchars(mb_strtolower((string)$u['insta'])) ?>"
                     data-email="<?= htmlspecialchars(mb_strtolower((string)$u['mail'])) ?>"
                     data-phone="<?= htmlspecialchars(preg_replace('/\s+/', '', (string)$u['telephone'])) ?>"
                     data-reste="<?= htmlspecialchars((string)$u['reste_a_payer']) ?>"
                     style="background-color: rgba(255, 255, 255, 0.1); color: white; padding: 2rem; text-align: center; font-size: 1rem">
                    <div class="card-body">
                        <p class="card-text">
                            <img class="emoji-inline" src="img/money-with-wings_1f4b8.png">
                            <?= htmlspecialchars(ucfirst(strtolower($u['prenom'])) . ' ' . strtoupper($u['nom'])) ?><br>
                            <a target="_blank" href="https://www.instagram.com/<?=htmlspecialchars($u['insta'])?>" ><?= "@". htmlspecialchars($u['insta']) ?></a><br>
                            <?= htmlspecialchars($u['telephone']) ?><br>
                            <?= htmlspecialchars($u['mail']) ?><br>
                            Il reste : <?= number_format($u['reste_a_payer'], 2, ',', ' ') ?> €
                        </p>
                        <a href="edit_user.php?id=<?= urlencode($u['id']) ?>" class="btn btn-outline-light btn-sm mt-3">✏️ Modifier</a>
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
            setTimeout(() => card.classList.add("show"), 30 * i);
        });

        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const amountFilter = document.getElementById('amountFilter');
        const resetBtn = document.getElementById('resetFilters');
        const visibleCount = document.getElementById('visibleCount');

        const userCards = document.querySelectorAll('.user-card');

        function normalize(s) {
            if (!s) return '';
            return ('' + s)
                .toLowerCase()
                .normalize('NFD')
                .replace(/\p{Diacritic}/gu, '')
                .trim();
        }

        function applyFilters() {
            const q = normalize(searchInput ? searchInput.value : '');
            const st = statusFilter ? statusFilter.value : 'all';
            const maxResteRaw = amountFilter ? amountFilter.value : '';
            const maxReste = maxResteRaw !== '' ? Number(maxResteRaw) : null;

            let shown = 0;

            userCards.forEach((card) => {
                const name = normalize(card.dataset.name);
                const insta = normalize(card.dataset.insta);
                const email = normalize(card.dataset.email);
                const phone = normalize((card.dataset.phone || '').replace(/\s+/g, ''));
                const reste = Number(card.dataset.reste || 0);

                let matchQ = true;
                if (q) {
                    matchQ = name.includes(q) || insta.includes(q) || email.includes(q) || phone.includes(q);
                }

                let matchStatus = true;
                if (st === 'due') matchStatus = reste > 0;
                if (st === 'paid') matchStatus = reste <= 0;

                let matchAmount = true;
                if (maxReste !== null && !Number.isNaN(maxReste)) {
                    matchAmount = reste <= maxReste;
                }

                const ok = matchQ && matchStatus && matchAmount;
                card.closest('.col-12')?.classList.toggle('d-none', !ok);
                if (ok) shown++;
            });

            if (visibleCount) visibleCount.textContent = String(shown);
        }

        if (searchInput) {
            searchInput.addEventListener('input', applyFilters);
        }
        if (statusFilter) {
            statusFilter.addEventListener('change', applyFilters);
        }
        if (amountFilter) {
            amountFilter.addEventListener('input', applyFilters);
        }
        if (resetBtn) {
            resetBtn.addEventListener('click', () => {
                if (searchInput) searchInput.value = '';
                if (statusFilter) statusFilter.value = 'all';
                if (amountFilter) amountFilter.value = '';
                applyFilters();
            });
        }

        applyFilters();
    });
</script>
</html>
