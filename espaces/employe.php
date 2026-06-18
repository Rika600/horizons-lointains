<?php
session_start();
$pageTitle = 'Espace Employé - Horizons Lointains';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Services/UtilisateurService.php';
require_once __DIR__ . '/../src/Services/ReservationService.php';

$pdo = Database::getConnection();
$utilisateurService = new UtilisateurService($pdo);
$reservationService = new ReservationService($pdo);

$message_erreur ='';

// =========== CONNEXION (si pas encore connecté) ==========
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

    // Vérifier le rôle (employé = 2, admin = 1)
    if (!isset($_SESSION['role_id']) || ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] !=2)) {
?>

    <div class="container my-5"> 
        <h1 class="text-center mb-5">Connexion Espace Employé</h1>

        <?php if ($message_erreur !=='') :?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($message_erreur) ?></div>
        <?php endif; ?>

        <form method="post" action="employe.php" class="col-md-6 mx-auto">
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

// ÉTAPE 3 : Employé connecté → traitement + affichage
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['changer_statut'])) {
    $reservationId = (int)$_POST['reservation_id'];
    $nouveauStatut = $_POST['nouveau_statut'];
    $reservationService->changerStatut($reservationId, $nouveauStatut);
    $message_succes = 'Statut mis à jour avec succès.';
}

$reservations = $reservationService->getToutesReservations();
?>

<!-- Tableau des réservations -->
<div class="container my-5">
    <h1 class="text-center mb-5">Espace Employé — Bonjour <?= htmlspecialchars($_SESSION['prenom']) ?></h1>

    <?php if (isset($message_succes)) : ?>
        <div class="alert alert-success text-center"><?= htmlspecialchars($message_succes) ?></div>
    <?php endif; ?>

    <h2 class="mb-4">Gestion des réservations</h2>

    <?php if (empty($reservations)) : ?>
        <p>Aucune réservation pour le moment.</p>
    <?php else : ?>
        <?php foreach ($reservations as $r) : ?>
        <div class="card mb-3 p-3">
            <p><strong><?= htmlspecialchars($r['numero_reservation']) ?></strong> — <?= htmlspecialchars($r['email']) ?></p>
            <p>Séjour : <?= htmlspecialchars($r['titre']) ?> | <?= $r['nb_personnes'] ?> pers. | <?= number_format($r['prix_total'], 2, ',', ' ') ?> €</p>
            <p>Du <?= htmlspecialchars($r['date_depart']) ?> au <?= htmlspecialchars($r['date_retour']) ?></p>
            <p>Statut : <strong><?= htmlspecialchars($r['statut']) ?></strong></p>

            <!-- Formulaire changement de statut -->
            <form method="post" action="employe.php">
                <input type="hidden" name="reservation_id" value="<?= $r['reservation_id'] ?>">
                <input type="hidden" name="changer_statut" value="1">
                <select name="nouveau_statut" class="form-select form-select-sm d-inline w-auto">
                    <option value="en_attente" <?= $r['statut'] === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                    <option value="confirmee" <?= $r['statut'] === 'confirmee' ? 'selected' : '' ?>>Confirmée</option>
                    <option value="annulee" <?= $r['statut'] === 'annulee' ? 'selected' : '' ?>>Annulée</option>
                    <option value="terminee" <?= $r['statut'] === 'terminee' ? 'selected' : '' ?>>Terminée</option>
                </select>
                <button type="submit" class="btn btn-dark btn-sm">Mettre à jour</button>
            </form>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
