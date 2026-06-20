<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/config.php';

// 1. Connexion à MySQL
$pdo = Database::getConnection();

// 2. Récupérer les stats depuis MySQL
$sql ="
    SELECT s.sejour_id, s.titre,
            COUNT(r.reservation_id) AS nb_reservations,
            COALESCE(SUM(r.prix_total), 0) AS chiffre_affaires
    FROM sejour s
    LEFT JOIN reservation r ON s.sejour_id = r.sejour_id AND r.statut != 'annulee'
    GROUP BY s.sejour_id, s.titre
    ORDER BY s.sejour_id
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $stats = $stmt->fetchAll();

    // 3. Connexion à MongoDB Atlas
    $client = new MongoDB\Client(MONGODB_URI);
    $db = $client->horizons_lointains;
    $collection = $db->stats_reservations;

    // 4. Vider l'ancienne collection
    $collection->deleteMany([]);

    // 5. Insérer les nouvelles stats
    foreach ($stats as $s) {
        $collection->insertOne([
            'sejour_id'        => (int)$s['sejour_id'],
            'titre'            => $s['titre'],
            'nb_reservations'  => (int)$s['nb_reservations'],
            'chiffre_affaires' => (float)$s['chiffre_affaires'],
            'date_sync'        => new MongoDB\BSON\UTCDateTime()
        ]);
    }

    echo "Synchronisation terminée ! " . count($stats) . " séjours envoyés vers MongoDB.\n";
