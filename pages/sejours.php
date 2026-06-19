<?php
$pageTitle = 'Nos Sejours - Horizons Lointains';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Services/SejourService.php';

$pdo = Database::getConnection();
$sejourService = new SejourService($pdo);
$sejours = $sejourService->getSejourActifs();

// Récupérer les destinations pour les filtres
$filtres = $sejourService->getFiltresData();
$destinations = $filtres['destinations'];
?>

    <!-- ============ FILTRES============= -->
     <div class="container my-5">

     <div class="d-flex align-items-center mb-5">
        <button id="btn-toggle-filtres" class="btn p-0 me-3" aria-label="Ouvrir les filtres">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" viewBox="0 0 16 16">
            <path d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
        </svg>
    </button>  
    <h1 class="text-center flex-grow-1 m-0">Nos Séjours</h1>
</div>

<div id="filtres-panel" class="filtres-container mb-4" style="display: none;">
    <div class="row g-3">
        <div class="col">
            <label for="filtre-destination" class="form-label">Destination</label>
            <select id="filtre-destination" class="form-select">
                <option value="">Toutes</option>
                <?php foreach ($destinations as $d) : ?>
                    <option value="<?= $d->getDestinationId() ?>"><?= htmlspecialchars($d->getNom()) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <! -- Filtre prix maximum et durée minimum, boutons filtrer/réinitialiser -- >
        <div class="col-md-3">
            <label for="filtre-prix-max" class="form-label">Prix max (€/pers)</label>
            <select id="filtre-prix-max" class="form-select">
                <option value="">Tous</option>
                <option value="1000">1000 €</option>
                <option value="1500">1500 €</option>
                <option value="2000">2000 €</option>
                <option value="3000">3000 €</option>
            </select>
        </div>

        <div class="col-md-3">
            <label for="filtre-duree" class="form-label">Durée min (nuits)</label>
            <select id="filtre-duree" class="form-select">
                <option value="">Toutes</option>
                <option value="7">7 nuits</option>
                <option value="10">10 nuits</option>
            </select>
        </div>
    </div>

    <div class="mt-3">
        <button  type="button" id="btn-filtrer" class="btn btn-dark px-4">Filtrer</button>
        <button type="button" id="btn-reset" class="btn btn-outline-dark px-4 ms-2">Réinitialiser</button>
    </div>
</div>

<! -- ============ GRILLE DES SEJOURS ========== -- >
<div class="sejours-grid" id="sejours-grid">

<?php foreach ($sejours as $sejour) : ?>
    
    <!-- Carte d'un séjour : image, titre, prix, durée, lien détail -->
     <div class="sejour-wrapper">
            <div class="sejour-card">
                    <div class="sejour-top">
                        <div class="sejour-left">
                            <div class="sejour-image-wrapper">
                                <img src="<?= BASE_URL ?>images/<?= htmlspecialchars($sejour->getImage()) ?>"
                                     alt="<?= htmlspecialchars($sejour->getTitre()) ?>"
                                     class="sejour-image">
                                <a href="<?= BASE_URL ?>pages/detail-sejour.php?id=<?= $sejour->getSejourId() ?>" class="sejour-overlay">
                                    <span class="overlay-button">Voir le détail</span>
                                </a>
                            </div>
                        </div>

                        <div class="sejour-infos">
                            <h3 class="sejour-titre"><?= htmlspecialchars($sejour->getTitre())  ?></h3>
                            <hr class="sejour-line mb-3">

                            <p class="sejour-description"><?= htmlentities($sejour->getDescription()) ?></p>

                            <p class="prix">
                                <?= number_format($sejour->getPrixPersonne(), 2, ',', '') ?> € par personne,<br>
                                <?= $sejour->getDureeNuits() ?> nuits.
                            </p>

                            <a href="<?= BASE_URL ?>pages/detail-sejour.php?id=<?= $sejour->getSejourId() ?>" class="btn btn-dark">
                                Voir le détail                       
                        </a>
                        </div>
                    </div>
            </div>
     </div>
     <?php endforeach; ?>

</div>

<!-- Variable BASE_URL accessible en JavaScript + chargement du fichier de filtres -->
<script>var BASE_URL = '<?= BASE_URL ?>';</script>
<script src="<?= BASE_URL ?>js/filtres.js"></script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>