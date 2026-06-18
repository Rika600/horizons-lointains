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

<!-- Page détail d'un séjour : image, infos, équpements, prix -->
 <div class="container my-5">
    <h1 class="text-center mb-5"><?= htmlspecialchars($sejour->getTitre()) ?></h1>

 <div class="sejour-card">
        <div class="sejour-top">
            <!-- Image à gauche -->
            <div class="sejour-left">
                <div class="sejour-image-wrapper">
                    <img src="<?= BASE_URL ?>images/<?= htmlspecialchars($sejour->getImage()) ?>" 
                         alt="<?= htmlspecialchars($sejour->getTitre()) ?>"
                         class="sejour-image">
                </div>
            </div>   

            <!-- Infos à droite -->
             <div class="sejour-infos">
                <p><?= htmlspecialchars($sejour->getDescription()) ?></p>

                <h4>Equipements</h4>
                <ul>
                    <?php foreach ($equipements as $e) : ?>
                        <li><?= htmlspecialchars($e->getLibelle()) ?></li>
                        <?php endforeach; ?>
                </ul>

                <p><strong>Inclus :</strong> <?= htmlspecialchars($sejour->getPrixComprend()) ?></p>
                <p><strong>Non inclus :</strong> <?= htmlspecialchars($sejour->getPrixComprendPas()) ?></p>

                <p class="prix">
                    <?= number_format($sejour->getPrixPersonne(), 2, ',', ' ') ?> € par personne,<br>
                    <?= $sejour->getDureeNuits() ?> nuits.
                </p>
             </div>
        </div>

        <!-- Bouton réserver -->
         <div class="text-center my-4">
            <a href="<?= BASE_URL ?>pages/reservation.php?sejour_id=<?= $sejour->getSejourId() ?>" class="btn btn-dark px-5 py-2">
                Réserver ce séjour
            </a>
         </div>
       </div>
     </div>

     <?php require_once __DIR__ . '/../includes/footer.php'; ?>