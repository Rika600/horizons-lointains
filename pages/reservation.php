<?php
session_start();
$pageTitle = 'Nos Sejours - Horizons Lointains';
require_once __DIR__ . '/../includes/header.php';
require_once  __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Services/ReservationService.php';
require_once __DIR__ . '/../src/Services/SejourService.php';
require_once __DIR__ . '/../src/Services/DestinationService.php';
require_once __DIR__ . '/../src/Services/UtilisateurService.php';

$pdo = Database::getConnection();
$reservationService = new ReservationService($pdo);
$sejourService = new SejourService($pdo);
$destinationsService = new DestinationService($pdo); 
$utilisateurService =  new UtilisateurService($pdo); 

//Récupérer l'id du séjour depuis l'URL
$sejourId = isset($_GET['sejour_id']) ? (int)$_GET['sejour_id'] : 0;

$sejourComplet = $sejourService->getSejourComplet($sejourId);

if (!$sejourComplet) {
    header ('Location: ' . BASE_URL . 'pages/sejours.php');
    exit;
}

$sejour = $sejourComplet['sejour'];

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $dateDepart = $_POST['date_depart'] ?? '';
    $dateRetour = $_POST['date_retour'] ?? '';
    $nbPersonnes = (int)($_POST['nb_personnes'] ?? 0);


    if ($nom === '' || $prenom === '' || $email === '' || $dateDepart === '' || $dateRetour === '') {
        $message_erreur = 'Tous les champs sont obligatoires.';
    } else if ($nbPersonnes < 1) {
        $message_erreur = 'Le nombre de persones doit être au moins 1.';
    } else if ($dateDepart >= $dateRetour) {
        $message_erreur = 'La date de retour doit être après la date de départ.';
    } else {
        $prix = $reservationService->calculerPrix(
            $sejour->getPrixPersonne(),
            $nbPersonnes
        );

        // Regrouper les infos pour la réservation
        $infos = [
            'date_depart' => $dateDepart,
            'date_retour' => $dateRetour,
            'nb_personnes' => $nbPersonnes,
            'email'        => $email,
            'utilisateur_id' => $_SESSION['utilisateur_id'] ?? null
        ];

        $result = $reservationService->creerReservation($sejourId, $infos, $prix);
        $numero = $result['numero'];

        $message_succes = 'Réservation ' . $numero . ' enregistrée ! Total : ' . number_format($prix['total'], 2, ',', ' ') . ' €';
    }
}
?>

<div class="container-my5">

<!-- Info réduction -->
 <div class="alert alert-info">
    <strong>Réduction :</strong> Une réduction de 10% est appliquée pour toute réservation de 5 personnes ou plus.
</div>

<h1 class="text-center mb-5">Réservation</h1>

<?php if (isset($message_succes)) :?>
    <div class="alert alert-success text-center"><?= htmlspecialchars($message_succes) ?></div>
    <?php endif; ?>

   <?php if (isset($message_erreur)) :?>
    <div class="alert alert-danger text-center"><?= htmlspecialchars($message_erreur) ?></div>
    <?php endif; ?> 

    <form method="post" action="reservation.php?sejour_id=<?=  $sejourId ?>" class="formulaire-reservation">
        <div class="row">

        <!-- COLONNE GAUCHE : Vos informations -->
         <div class="col-md-6">
            <h2 class="text-center mb-4">Vos infomations</h2>

            <label for="nom">Nom :
                <input id="nom" name="nom" type="text" required>
            </label>

            <label for="prenom">Prénom :
                <input id="prenom" name="prenom" type="text" required>
            </label>

            <label for="email">Email :
                <input id="email" name="email" type="email" required>
            </label>
         </div>

         <!-- COLONNE DROITE : Le séjour choisi -->
          <div class="col-md-6">
                <h2 class="text-center mb-4">Votre séjour></h2>

              <label>Séjour choisi :
                 <input type="text" value="<?= htmlspecialchars($sejour->getTitre()) ?>" readonly>
               </label>
              
                <label for="date_depart">Date de départ :
                    <input id="date_depart" name="date_depart" type="date" required>
                </label>
              
                <label for="date_retour">Date de retour :
                     <input id="date_retour" name="date_retour" type="date" required>
                </label>
              
                <label for="nb_personnes">Nombre de personnes :
                    <input id="nb_personnes" name="nb_personnes" type="number" min="1" value="1" required>
                </label>
              
             <!-- Récap prix -->
                <label>Prix par personne :
                  <input type="text" value="<?= number_format($sejour->getPrixPersonne(), 2, ',', ' ') ?> €" readonly>
                </label>
              
                <label><strong>Total estimé :</strong>
                    <input id="total_estime" type="text" value="" readonly>
                </label>
            </div>  
          </div>

          <div class="text-center mt-4">
            <input type="submit" value="Réserver" class="btn btn-dark">
        </div>
    </form>
</div>

<script>var BASE_URL = '<?= BASE_URL ?>';</script>
<script>var PRIX_PERSONNE = <?=  $sejour->getPrixPersonne() ?>;</script>
<script src="<?= BASE_URL ?>js/calcul-prix.js"></script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?> 