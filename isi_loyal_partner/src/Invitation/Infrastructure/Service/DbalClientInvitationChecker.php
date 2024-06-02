<?php

declare(strict_types=1);

namespace Invitation\Infrastructure\Service;

use Doctrine\ORM\EntityManagerInterface;
use Invitation\Domain\ClientEmail;
use Invitation\Domain\Invitation;
use Invitation\Domain\LoyaltyProgram;
use Invitation\Domain\Service\ClientInvitationChecker;

final readonly class DbalClientInvitationChecker implements ClientInvitationChecker
{
    public function __construct(private EntityManagerInterface $invitationEntityManager)
    {
    }

    public function isClientAlreadyInvited(LoyaltyProgram $loyaltyProgram, ClientEmail $clientEmail): bool
    {
        $result = $this->invitationEntityManager->getRepository(Invitation::class)->findOneBy(
            ['loyaltyProgram' => $loyaltyProgram, 'clientEmail.email' => $clientEmail->email]
        );

        return (bool) $result;
    }
}