<?php

declare(strict_types=1);

namespace ClientPurchases\Infrastructure\Repository;

use ClientPurchases\Domain\Client;
use ClientPurchases\Domain\LoyaltyProgram;
use ClientPurchases\Domain\LoyaltyProgramClient;
use ClientPurchases\Domain\LoyaltyProgramClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final readonly class DoctrineLoyaltyProgramClientRepository implements LoyaltyProgramClientRepository
{
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->repository = $this->entityManager->getrepository(LoyaltyProgramClient::class);
    }

    public function save(LoyaltyProgramClient $loyaltyProgramClient): void
    {
        $this->entityManager->persist($loyaltyProgramClient);
    }

    public function findByClientAndLoyaltyProgram(Client $client, LoyaltyProgram $loyaltyProgram): LoyaltyProgramClient
    {
        return $this->repository->findOneBy(['client' => $client, 'loyaltyProgram' => $loyaltyProgram]);
    }
}