<?php

require_once __DIR__ .'/../Repository/ReservationRepository.php';

class ReservationService
{
    private ReservationRepository $reservationRepository;

    public function __construct(PDO $pdo)
    {
        $this->reservationRepository = new ReservationRepository($pdo);
    }
    
    public function calculerPrix(float $prixPersonne, int $nbPersonnes): array
    {
        $prixBase = $prixPersonne * $nbPersonnes;

        $reduction = 0;
        if($nbPersonnes >= 5) {
            $reduction = $prixBase * 0.10;
        }

        $total = $prixBase - $reduction;

        return [
            'prix_base' => $prixBase,
            'reduction' => $reduction,
            'total'     =>$total
        ];
    }

    public function creerReservation(int $sejourId, array $infos, array $prix): array
    {
        $numero = 'RES-' . date('Ymd') . '-' . rand(1000, 9999);

        $data = [
            ':numero_reservation' => $numero,
            ':utilisateur_id'     => $infos['utilisateur_id'] ?? null,
            ':email'              => $infos['email'],
            ':sejour_id'          => $sejourId,
            ':date_depart'        => $infos['date_depart'],
            ':date_retour'        => $infos['date_retour'],
            ':nb_personnes'       => $infos['nb_personnes'],
            ':prix_total'         => $prix['total'],
            ':statut'             => 'en_attente'
        ];

        $reservationId = $this->reservationRepository->create($data);
        return ['id' => $reservationId, 'numero' => $numero];
    }

    public function getReservationClient(int $utilisateurId): array
    {
        return $this->reservationRepository->findByUtilisateur($utilisateurId);
    }
 
    public function changerStatut(int $reservationId, string $statut): void
    {
        $this->reservationRepository->updateStatut($reservationId, $statut);
    }

}