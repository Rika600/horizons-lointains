<?php

require_once __DIR__ .'/../Repository/DestinationRepository.php';

class DestinationService
{
    private DestinationRepository $destinationRepository;

    public function __construct(PDO $pdo)
    {
        $this->destinationRepository = new DestinationRepository($pdo);
    }

        public function getDestinations(): array 
        {
            return $this->destinationRepository->findAll();
        }
    }