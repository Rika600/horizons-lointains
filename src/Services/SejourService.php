<?php

require_once __DIR__ .'/../Repository/SejourRepository.php';

class SejourService
{
    private SejourRepository $SejourRepository;

    public function __construct(PDO $pdo)
    {
        $this->SejourRepository = new SejourRepository($pdo);
    }

    public function getSejourActifs(): array
    {
        return $this->SejourRepository->findAll();
    }

    public function getSejourComplet(int $id): ?array
    {
        $sejour = $this->SejourRepository->findById($id);
        if (!$sejour) return null;

        $equipements = $this->SejourRepository->findEquipements($id);

        return [
            'sejour' => $sejour,
            'equipements' => $equipements
        ];
    }
}