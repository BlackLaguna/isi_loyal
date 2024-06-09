<?php

declare(strict_types=1);

namespace LoyaltyProgram\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use LoyaltyProgram\Domain\LoyaltyProgram;
use LoyaltyProgram\Domain\LoyaltyProgramRepository;
use LoyaltyProgram\Domain\Partner;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DoctrineLoyaltyProgramRepository implements LoyaltyProgramRepository
{
    private EntityRepository $loyaltyProgramRepository;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        $this->loyaltyProgramRepository = $entityManager->getRepository(LoyaltyProgram::class);
    }

    public function findAllForPartner(Partner $partner): array
    {
        return $this->loyaltyProgramRepository->findBy(['partner' => $partner]);
    }

    public function findByPartnerAndName(Partner $partner, string $loyaltyProgramName): LoyaltyProgram
    {
        $loyaltyProgram = $this->loyaltyProgramRepository
            ->findOneBy(['partner' => $partner, 'name' => $loyaltyProgramName]);

        if (null === $loyaltyProgram) {
            throw new NotFoundHttpException();
        }

        return $loyaltyProgram;
    }

    public function persist(LoyaltyProgram $loyaltyProgram): void
    {
        $this->entityManager->persist($loyaltyProgram);
    }
}