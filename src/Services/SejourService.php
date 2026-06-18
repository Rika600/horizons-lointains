<?php

require_once __DIR__ .'/../Repository/SejourRepository.php';

class SejourService
{
    private SejourRepository $sejourRepository;

    public function __construct(PDO $pdo)
    {
        $this->sejourRepository = new SejourRepository($pdo);
    }

    public function getSejourActifs(): array
    {
        return $this->sejourRepository->findAll();
    }

    public function getSejourComplet(int $id): ?array
    {
        $sejour = $this->sejourRepository->findById($id);
        if (!$sejour) return null;

        $equipements = $this->sejourRepository->findEquipements($id);

        return [
            'sejour' => $sejour,
            'equipements' => $equipements
        ];
    }
    
    public function getFiltresData(): array
    {
        return [
            'destinations' => $this->sejourRepository->findAllDestinations()
        ];
    }
}