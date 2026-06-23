<?php
$pageTitle = 'Suivre ma réservation - Horizons Lointains';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Services/ReservationService.php';

$pdo = Database::getConnection();
$reservationService = new ReservationService($pdo);

$reservation = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = trim($_POST['numero'] ?? '');
    $email = trim($_POST['email'] ??'');

    if ($numero === '' || $email ==='') {
        $message_erreur = "Veuillez renseigner le numéro et l\'email.";
    } else {
        $reservation = $reservationService->getReservationParNumeroEtEmail($numero, $email);
     if (!$reservation) {
        $message_erreur = 'Aucune réservation trouvée avec informations.';
        }
    } 
 }
 ?>

 <div class="container my-5">
     <h1 class="text-center mb-5">Suivre ma réservation</h1> 
     
     <?php if (isset($message_erreur)) : ?>
             <div class="alert alert-danger text-center"><?= htmlspecialchars($message_erreur) ?></div>
      <?php endif; ?>

      <form method="post" action="suivre-reservation.php" class="col-md-6 mx-auto">
    <div class="mb-3">
        <label for="numero" class="form-label">Numéro de réservation :</label>
        <input id="numero" name="numero" type="text" placeholder="RES-20260618-1234" required class="form-control">
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email :</label>
        <input id="email" name="email" type="email" required class="form-control">
    </div>

    <div class="text-center mt-4">
        <input type="submit" value="Rechercher" class="btn btn-dark">
    </div>
</form>

    <?php if ($reservation) :?>
        <div class="card mt-5 p-4">
            <h2 class="text-center"><?= htmlspecialchars($reservation['titre']) ?></h2>
             <p><strong>Numéro :</strong> <?= htmlspecialchars($reservation['numero_reservation']) ?></p>
             <p><strong>Statut :</strong> <?= htmlspecialchars($reservation['statut']) ?></p>
             <p><strong>Du :</strong> <?= htmlspecialchars($reservation['date_depart']) ?> <strong>au :</strong> <?= htmlspecialchars($reservation['date_retour']) ?></p>
             <p><strong>Nombre de personnes :</strong> <?= $reservation['nb_personnes'] ?></p>
             <p><strong>Total :</strong> <?= number_format($reservation['prix_total'], 2, ',', ' ') ?> €</p>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>