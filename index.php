<?php 
require_once 'src/Database.php';
require_once 'src/Services/DestinationService.php';

$pdo = Database::getConnection();
$destinationService = new DestinationService($pdo);
$destinations = $destinationService->getDestinations();

$pageTitle = 'Accueil - Horizons Lointains';
require_once 'includes/header.php';
?>

<!-- Section Hero -->
<div class="hero text-center text-white py-5" style="background: url('<?= BASE_URL ?>images/hero.jpg') center/cover no-repeat; min-height: 500px;">
    <div class="py-5 mt-5">
        <h1>Horizons Lointains</h1>
        <p>Votre agence de voyage depuis 2010</p>
        <a href="<?= BASE_URL ?>pages/sejours.php" class="btn btn-dark mt-3">Découvrer nos séjours</a>
    </div>
</div>

<!-- Section Destinations -->
<div class="container my-5">
    <h2 class="text-center mb-4">NOS DESTINATIONS</h2>
    <hr>
    <div class="row">
        <?php foreach ($destinations as $d): ?>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <img src="<?= BASE_URL ?>images/<?= htmlspecialchars($d->getImage()) ?>" 
                         class="card-img-top" alt="<?= htmlspecialchars($d->getNom()) ?>"
                         style="height: 250px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h3><?= htmlspecialchars($d->getNom()) ?></h3>
                        <p><?= htmlspecialchars($d->getDescription()) ?></p>
                        <a href="<?= BASE_URL ?>pages/sejours.php?destination=<?= $d->getDestinationId() ?>" 
                           class="btn btn-dark">Voir les séjours</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>