<?php

class Reservation
{
    private int $reservation_id;
    private string $numero_reservation;
    private int $utilisateur_id;
    private int $sejour_id;
    private string $date_depart;
    private string $date_retour;
    private int $nb_personnes;
    private float $prix_total;
    private string $statut;
    private string $created_at;

    public function getReservationId(): int {
         return $this->reservation_id; 
         }

    public function getNumeroReservation(): string { 
        return $this->numero_reservation; 
        }

    public function getUtilisateurId(): int { 
        return $this->utilisateur_id; 
        }

    public function getSejourId(): int { 
        return $this->sejour_id; 
        }

    public function getDateDepart(): string { 
        return $this->date_depart; 
        }

    public function getDateRetour(): string {
         return $this->date_retour; 
         }

    public function getNbPersonnes(): int {
         return $this->nb_personnes; 
         }

    public function getPrixTotal(): float {
         return $this->prix_total; 
         }

    public function getStatut(): string {
         return $this->statut; 
         }

    public function getCreatedAt(): string {
         return $this->created_at; 
         }
}