<?php
session_start();
$pageTitle = 'Espace Admin - Horizons Lointains';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../src/Services/UtilisateurService.php';
require_once __DIR__ . '/../src/Services/ReservationService.php';
require_once __DIR__ . '/../src/Services/SejourService.php';

$pdo = Database::getConnection();
$utilisateurService = new UtilisateurService($pdo);
$reservationService = new ReservationService($pdo);
$sejourService = new SejourService($pdo);
$client = new MongoDB\Client(MONGODB_URI);

$message_erreur =''; 

// Traitement connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['connexion'])) {
    $email = trim($_POST['email'] ?? '');
    $motDePasse = $_POST['mot_de_passe'] ?? '';
    $result = $utilisateurService->connecter($email, $motDePasse);
    if ($result['success']) {
        $utilisateur = $result['utilisateur'];
        $_SESSION['utilisateur_id'] = $utilisateur->getUtilisateurId();
        $_SESSION['role_id'] = $utilisateur->getRoleId();
        $_SESSION['prenom'] = $utilisateur->getPrenom();
    } else {
        $message_erreur = $result['erreur'];
    }
}

// Vérifier rôle admin uniquement
if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
?>
    <div class="container my-5">
        <h1 class="text-center mb-5">Connexion Espace Admin</h1>
        <?php if ($message_erreur !== '') : ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($message_erreur) ?></div>
        <?php endif; ?>
        <form method="post" action="espace-admin.php" class="col-md-6 mx-auto">
            <input type="hidden" name="connexion" value="1">
            <label for="email">Email :
                <input id="email" name="email" type="email" required>
            </label>
            <label for="mot_de_passe">Mot de passe :
                <input id="mot_de_passe" name="mot_de_passe" type="password" required>
            </label>
            <div class="text-center mt-4">
                <input type="submit" value="Se connecter" class="btn btn-dark">
            </div>
        </form>
    </div>
<?php
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

// Récupérer les stats MySQL
$reservations = $reservationService->getToutesReservations();
$nbReservations = count($reservations);
$chiffreAffaires = array_sum(array_column($reservations, 'prix_total'));

// Récupérer les stats MongoDB
$db = $client->horizons_lointains;
$collection = $db->stats_reservations;
$stats = $collection->find([], ['sort' => ['sejour_id' => 1]]);
$stats = iterator_to_array($stats);

// Préparer les données pour Chart.js
$labels = [];
$nbReservationsChart = [];
$ca = [];
foreach ($stats as $s) {
    $labels[] = $s['titre'];
    $nbReservationsChart[] = (int)$s['nb_reservations'];
    $ca[] =  (float) $s['chiffre_affaires'];
}

// Récupérer les séjours
$sejours = $sejourService->getSejourActifs();
?>

<!-- Espace Admin -->
<div class="container my-5">
    <h1 class="text-center mb-5">Espace Admin — Bonjour <?= htmlspecialchars($_SESSION['prenom']) ?></h1>

    <!-- Stats -->
    <div class="row mb-5">
        <div class="col-md-6">
            <div class="card p-4 text-center">
                <h3>Réservations</h3>
                <p class="fs-1"><?= $nbReservations ?></p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-4 text-center">
                <h3>Chiffre d'affaires</h3>
                <p class="fs-1"><?= number_format($chiffreAffaires, 2, ',', ' ') ?> €</p>
            </div>
        </div>
    </div>

    <!-- Liste des séjours -->
    <h2 class="mb-4">Gestion des séjours</h2>
    <?php foreach ($sejours as $s) : ?>
        <div class="card mb-2 p-3">
            <p><strong><?= htmlspecialchars($s->getTitre()) ?></strong> — <?= number_format($s->getPrixPersonne(), 2, ',', ' ') ?> €/pers — <?= $s->getDureeNuits() ?> nuits</p>
        </div>
    <?php endforeach; ?>


    <!-- Graphique -->
    <h2 class="mt-5 mb-3">Réservations par séjour</h2>
    <canvas id="graphique-reservations" height="100"></canvas>
</div>

 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 <script>
    var ctx = document.getElementById('graphique-reservations').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels:<?= json_encode($labels) ?>,
            datasets: [{
                label: 'Nombre de réservations',
                data:<?= json_encode($nbReservationsChart) ?>,
                backgroundColor: ['#1a6b8a', '#2d9cdb', '#27ae60', '#f39c12']
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true }}
        }
    });
    </script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>