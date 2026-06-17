<?php

class Sejour
{
    private int $sejour_id;
    private string $titre;
    private ?string $description;
    private ?string $image;
    private float $prix_personne;
    private int $duree_nuits;
    private ?int $superficie_m2;
    private ?string $prix_comprend;
    private ?string $prix_comprend_pas;
    private bool $actif;
    private int $destination_id;
    private int $hebergement_id;

    //getters
    public function getSejourId() : int 
    {
         return $this->sejour_id; 
    }

    public function getTitre() : string {
        return $this->titre;
    } 

    public function getDescription() : ?string {
        return $this->description;
    }

    public function getImage() : ?string {
         return $this->image;

    }

    public function getPrixPersonne(): float { 
        return $this->prix_personne; 
     }

    public function getDureeNuits(): int {
        return $this->duree_nuits; 
    }

    public function getSuperficieM2(): ?int { 
        return $this->superficie_m2; 
    }

    public function getPrixComprend(): ?string { 
        return $this->prix_comprend; 
     }

    public function getPrixComprendPas(): ?string { 
        return $this->prix_comprend_pas; 
    }

    public function isActif(): bool { 
        return $this->actif; 
    }

    public function getDestinationId(): int {
         return $this->destination_id; 
    }

    public function getHebergementId(): int { 
        return $this->hebergement_id; 
    }
}