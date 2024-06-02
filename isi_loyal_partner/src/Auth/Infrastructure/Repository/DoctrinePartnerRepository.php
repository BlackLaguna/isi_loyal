<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Repository;

use Auth\Domain\Exception\UserNotFoundException;
use Auth\Domain\Partner;
use Auth\Domain\PartnerRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class DoctrinePartnerRepository extends ServiceEntityRepository implements PartnerRepository
{
    public function __construct(private ManagerRegistry $registry)
    {
        parent::__construct($registry, Partner::class);
    }

    /** @throws UserNotFoundException */
    public function getUserByEmail(string $email): Partner
    {
        $user = $this->getEntityManager()->find(Partner::class, $email);

        if (is_null($user)) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function save(Partner $user): void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function getUserByEmailCanonical(string $emailCanonical): Partner
    {
        $user = $this->getEntityManager()->getRepository(Partner::class)->findOneBy(['emailCanonical' => $emailCanonical]);

        if (is_null($user)) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}