<?php

require_once __DIR__ .'/../Entity/Destination.php';

class DestinationRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

      public function findAll(): array
    {
        $sql = "SELECT destination_id, nom, description, image FROM destination ORDER BY nom ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_CLASS, Destination::class);
    }
}