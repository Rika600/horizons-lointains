<?php

require_once __DIR__ .'/../Repository/UtilisateurRepository.php';

class UtilisateurService
{
    private UtilisateurRepository $utilisateurRepository;

    public function __construct(PDO $pdo)
    {
        $this->utilisateurRepository = new UtilisateurRepository($pdo);
    }

    public function connecter(string $email, string $mot_de_passe): array
    {
        $utilisateur  = $this->utilisateurRepository->findByEmail($email);

        if (!$utilisateur) {
            return ['success' => false, 'erreur' => 'Email ou mot de passe incorrect.'];
        }

        if(!password_verify($mot_de_passe, $utilisateur->getMotDePasse())) {
            return ['success' => false, 'erreur' => 'Email ou mot de passe incorrect.'];
        }

        return ['success' => true, 'utilisateur' => $utilisateur];
    }
    
    public function inscrire(array $data): array
    {
        if(empty($data['email']) || empty($data['mot_de_passe']) || empty($data['nom']) || empty($data['prenom'])) {
           return ['success' => false, 'erreur' => 'Tous les champ sont obligatoires.'];
        }

        if(!filter_var($data['email'],  FILTER_VALIDATE_EMAIL)) {
            return['success' =>false, 'erreur' => 'Email invalide'];
        }

        if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{10,}$/', $data['mot_de_passe'])) {
            return['success' => false, 'erreur' => 'Le mot de passe doit contenir au moins 10 caractère, une majuscule, une minuscule, un chiffre et un caractère spécial.'];
        }

        if($data['mot_de_passe'] !==$data['mot_de_passe_confirm']) {
            return['success' => false, 'erreur' => 'Les mots de passe ne correspondent pas.'];
        }

        if($this->utilisateurRepository->emailExists($data['email'])) {
            return['success' => false, 'erreur' => 'Cet email est déjà utilisé.'];
        }

        $id = $this->utilisateurRepository->create(
            $data['nom'], $data['prenom'], $data['email'], $data['mot_de_passe'],
        );

        return ['success' => true, 'utilisateur_id' => $id];
        
    }
    
    public function demanderResetPassword(string $email): string
    {
        $token = bin2hex(random_bytes(32));
        $expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $this->utilisateurRepository->storeResetToken($email, $token, $expiration);
        return $token;
    }

    public function verifierToken(string $token): ?Utilisateur
    {
        return $this->utilisateurRepository->findByToken($token);
    }

    public function resetPassword(string $token, string $newPassword): array
    {
        $utilisateur = $this->utilisateurRepository->findByToken($token);

        if(!$utilisateur) {
            return ['success' => false, 'erreur' => 'Token invalide ou expiré.'];
        }

        if(strlen($newPassword) < 8) {
            return ['success' => false,  'erreur' => 'Le mot de passe doit contenir au moins 8 caractères'];
        }

        $this->utilisateurRepository->updateMotDePasse($utilisateur->getUtilisateurId(), $newPassword);
        return ['success' => true];
    }
    }