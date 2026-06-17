<?php

class Utilisateur
{
    private int $utilisateur_id;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $mot_de_passe;
    private int $role_id;
    private ?string $token_reset;
    private ?string $token_expiration;

    public function getUtilisateurId(): int {
         return $this->utilisateur_id; 
         }

    public function getNom(): string {
         return $this->nom; 
         }

    public function getPrenom(): string {
         return $this->prenom; 
         }

    public function getEmail(): string {
         return $this->email;
          }

    public function getMotDePasse(): string {
         return $this->mot_de_passe; 
         }

    public function getRoleId(): int {
         return $this->role_id; 
         }

    public function getTokenReset(): ?string { 
        return $this->token_reset;
         }

    public function getTokenExpiration(): ?string { 
        return $this->token_expiration; 
        }
}