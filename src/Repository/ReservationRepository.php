<?php

require_once __DIR__ .'/../Entity/Reservation.php';

class ReservationRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(array $data): int
    {
        $sql= "INSERT INTO reservation(numero_reservation, utilisateur_id, email, sejour_id, date_depart, 
        date_retour, nb_personnes, prix_total, statut)
        VALUES (:numero_reservation, :utilisateur_id, :email, :sejour_id, :date_depart, :date_retour, :nb_personnes, :prix_total, :statut )";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        return(int) $this->pdo->lastInsertId();
    }

    public function findByUtilisateur(int $utilisateurId): array
    {
        $sql = "SELECT r.*, s.titre, s.image, s.prix_personne
        FROM reservation r
        JOIN sejour s ON r.sejour_id = s.sejour_id
        WHERE r.utilisateur_id = :utlisateur_id
        ORDER BY r.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':utilisateur_id' => $utilisateurId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatut(int $reservationId, string $statut): void
    {
        $sql = "UPDATE reservation SET statut = :statut WHERE reservation_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
        ':statut' => $statut,
        ':id' => $reservationId
        ]);
    }

    public function findByNumeroEtEmail(string $numero, string $email): ?array
    {
        $sql= "SELECT r.*, s.titre, s.image, s.prix_personne
               FROM reservation r
               JOIN sejour s ON r.sejour_id = s.sejour_id
               WHERE r.numero_reservation = :numero AND r.email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':numero' => $numero, ':email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;

    }
}