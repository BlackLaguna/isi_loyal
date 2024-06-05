<?php

declare(strict_types=1);

namespace Invitation\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Invitation\Domain\Invitation;
use Invitation\Domain\InvitationRepository;

final class DoctrineInvitationRepository implements InvitationRepository
{
    public function __construct(private readonly EntityManagerInterface $invitationEntityManager)
    {
    }

    public function save(Invitation $invitation): void
    {
        $this->invitationEntityManager->persist($invitation);
        $this->invitationEntityManager->flush();
    }
}