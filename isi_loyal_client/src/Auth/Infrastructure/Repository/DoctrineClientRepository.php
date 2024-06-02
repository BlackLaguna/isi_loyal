<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Repository;

use Auth\Domain\Exception\UserNotFoundException;
use Auth\Domain\Client;
use Auth\Domain\ClientRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class DoctrineClientRepository extends ServiceEntityRepository implements ClientRepository
{
    public function __construct(private ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    /** @throws UserNotFoundException */
    public function getUserByEmail(string $email): Client
    {
        $user = $this->getEntityManager()->find(Client::class, $email);

        if (is_null($user)) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function save(Client $user): void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function getUserByEmailCanonical(string $emailCanonical): Client
    {
        $user = $this->getEntityManager()->getRepository(Client::class)->findOneBy(['emailCanonical' => $emailCanonical]);

        if (is_null($user)) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}