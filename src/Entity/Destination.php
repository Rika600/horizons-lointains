<?php

class Destination {
    private int $destination_id;
    private string $nom;
    private ?string $description; 
    private ?string $image;

    public function getDestinationId(): int {
        return $this->destination_id;
    }
    public function getNom(): string { 
        return $this->nom; 
    }

    public function getDescription(): ?string {
         return $this->description; 
    }

    public function getImage(): ?string {
         return $this->image; 
    }
}