<?php if(session_status() === PHP_SESSION_NONE) session_start(); ?>
<?php require_once __DIR__ .'/../config.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Horizons Lointains' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
</head>
<body>
    
<header>
    <nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="<?= BASE_URL ?>">Horizons Lointains</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-5">
                    <li class="nav-item"><a class="nav-link text-white" href="<?= BASE_URL ?>">ACCUEIL</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="<?= BASE_URL ?>pages/sejours.php">NOS SEJOURS</a></li>

                    <?php if (!isset($_SESSION['utilisateur_id'])) : ?>
                        <li class="nav-item"><a class="nav-link text-white" href="<?= BASE_URL ?>pages/suivre-reservation.php">SUIVRE MA RESERVATION</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="<?= BASE_URL ?>espaces/employe.php">CONNEXION</a></li>
                    <?php else : ?>
                        <?php if ($_SESSION['role_id'] == 2 || $_SESSION['role_id'] == 1) : ?>
                            <li class="nav-item"><a class="nav-link text-white" href="<?= BASE_URL ?>espaces/employe.php">EMPLOYÉ</a></li>
                        <?php endif; ?>
                        <?php if ($_SESSION['role_id'] == 1) : ?>
                            <li class="nav-item"><a class="nav-link text-white" href="<?= BASE_URL ?>espaces/espace-admin.php">ADMIN</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link text-white" href="<?= BASE_URL ?>pages/deconnexion.php">DÉCONNEXION</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>

<main id="main-page" style="min-height: 80vh;">