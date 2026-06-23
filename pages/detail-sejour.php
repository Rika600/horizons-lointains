<?php
$pageTitle = 'Nos Sejours - Horizons Lointains';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Services/SejourService.php';

// 1. Récupérer l'id depuis l'URL
$sejourId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 2. Connexion BDD + instancier le modèle
$pdo = Database::getConnection();
$sejourService = new SejourService($pdo);

// 3. Récupérer CE séjour
$sejourComplet = $sejourService->getSejourComplet($sejourId);

// 4. Si le séjour n'existe pas, rediriger vers le catalogue
if(!$sejourComplet) {
    die("Séjour introuvable pour l'id :" . $sejourId);
}

// 5. Extraire les données du séjour complet
$sejour = $sejourComplet['sejour'];
$equipements = $sejourComplet['equipements'];
?>

<!-- Image pleine largeur avec encart prix -->
<div class="position-relative">
    <img src="<?= BASE_URL ?>images/<?= htmlspecialchars($sejour->getImage()) ?>" 
         alt="<?= htmlspecialchars($sejour->getTitre()) ?>"
         class="detail-image">
    
    <!-- Encart prix en bas à droite -->
    <div class="detail-prix-encart">
        <p class="text-uppercase small mb-1" style="color:#a0c4d0;">À partir de</p>
        <p class="fs-2 fw-bold text-white mb-0"><?= number_format($sejour->getPrixPersonne(), 0, ',', ' ') ?> €</p>
        <p class="text-white mb-3"><?= $sejour->getDureeNuits() ?> nuits</p>
        <hr style="border-color: rgba(255,255,255,0.3);">
        <a href="<?= BASE_URL ?>pages/reservation.php?sejour_id=<?= $sejour->getSejourId() ?>" class="btn btn-reserver w-100">
            Réserver ce séjour
        </a>
    </div>
</div>

<!-- Titre + infos en dessous -->
<div class="container my-5">
    <h1 class="mb-3"><?= htmlspecialchars($sejour->getTitre()) ?></h1>
    <p class="lead"><?= htmlspecialchars($sejour->getDescription()) ?></p>

    <div class="row mt-4">
        <div class="col-md-6">
            <h4>Équipements</h4>
            <ul>
                <?php foreach ($equipements as $e) : ?>
                    <li><?= htmlspecialchars($e->getLibelle()) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-md-6">
            <p><strong>Inclus :</strong> <?= htmlspecialchars($sejour->getPrixComprend()) ?></p>
            <p><strong>Non inclus :</strong> <?= htmlspecialchars($sejour->getPrixComprendPas()) ?></p>
        </div>
    </div>
</div>

     <?php require_once __DIR__ . '/../includes/footer.php'; ?>