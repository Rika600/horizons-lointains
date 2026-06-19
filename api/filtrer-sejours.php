<?php
require_once __DIR__ . '/../src/Database.php';

header ('Content-Type: application/json');

$pdo = Database::getConnection();

// Récupérer les filtres envoyés par le JS via l'URL
$destinationId = isset($_GET['destination']) ? (int)$_GET['destination'] : 0;
$prixMax = isset($_GET['prix_max']) && $_GET['prix_max'] !=='' ? (float) $_GET['prix_max'] : null;
$duree = isset($_GET['duree']) && $_GET['duree'] !== ''? (int)$_GET['duree'] : 0;

// Requête de base : tous les séjours actifs
$sql = "
    SELECT s.sejour_id, s.titre, s.description, s.image,
           s.prix_personne, s.duree_nuits, s.destination_id, s.hebergement_id
           FROM sejour s
           WHERE s.actif = TRUE 
    ";

    $params = [];

    // Ajouter les filtres seulement s'ils sont remplis 
    if ($destinationId > 0) {
        $sql .= " AND s.destination_id = :destination_id";
        $params[':destination_id'] = $destinationId;
    }

    if ($prixMax !== null) {
    $sql .= " AND s.prix_personne <= :prix_max";
    $params[':prix_max'] = $prixMax;
    }

    if ($duree > 0) {
        $sql .= " AND s.duree_nuits >= :duree";
        $params[':duree'] = $duree;
    }

    $sql .= " ORDER BY  s.sejour_id ASC";

    // Exécuter la requête
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $sejours = $stmt->fetchAll();

    // Préparer et renvoyer le JSON 
    $result = [];

    foreach ($sejours as $sejour) {
        // Formater le prix pour l'affichage
        $sejour['prix_formate'] = number_format($sejour['prix_personne'], 2, ',', ' ');
        $result[] = $sejour;
    }

    echo json_encode($result); 