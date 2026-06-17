<?php

class Equipement
{
    private int $equipement_id;
    private string $libelle;

    public function getEquipementId(): int { 
        return $this->equipement_id; 
        }

    public function getLibelle(): string { 
        return $this->libelle; 
        }
}