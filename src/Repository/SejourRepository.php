<?php

require_once __DIR__ . '/../Entity/Sejour.php';
require_once __DIR__ . '/../Entity/Equipement.php';
require_once __DIR__ . '/../Entity/Destination.php';

class SejourRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $sql="
            SELECT s.sejour_id, s.titre, s.description, s.image,
            s.prix_personne, s.duree_nuits, s.superficie_m2,
            s.prix_comprend, s.prix_comprend_pas, s.actif,
            s.destination_id, s.hebergement_id
            FROM sejour s
            WHERE s.actif = TRUE
            ORDER BY  s.sejour_id ASC
         ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_CLASS, Sejour::class);
    }

    public function findById(int $id): ?Sejour
    {
        $sql= "
             SELECT s.sejour_id, s.titre, s.description, s.image,
               s.prix_personne, s.duree_nuits, s.superficie_m2,
               s.prix_comprend, s.prix_comprend_pas, s.actif,
               s.destination_id, s.hebergement_id
        FROM sejour s
        WHERE s.sejour_id = :id
        AND s.actif = TRUE
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, Sejour::class);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findEquipements(int $sejourId): array
    {
        $sql = "
            SELECT e.equipement_id, e.libelle
            FROM equipement e
            JOIN sejour_equipement se ON e.equipement_id = se.equipement_id
            WHERE se.sejour_id = :sejour_id
            ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':sejour_id' => $sejourId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, Equipement::class);
    }

    public function findAllDestinations(): array {
        $sql = "SELECT destination_id, nom, description, image FROM destination ORDER BY nom ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_CLASS, Destination::class);
    }
}