<?php

declare(strict_types=1);

namespace Invitation\Application\CQRS\Handler;

use Invitation\Application\CQRS\Command\InviteClientToLoyaltyProgramCommand;
use Invitation\Domain\Exception\ClientAlreadyInvitedException;
use Invitation\Domain\Exception\InvalidLoyaltyProgramException;
use Invitation\Domain\Invitation;
use Invitation\Domain\InvitationRepository;
use Invitation\Domain\Service\ClientInvitationChecker;
use Invitation\Domain\Service\SendInvitationToClientEmail;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class InviteClientToLoyaltyProgramHandler
{
    public function __construct(
        private ClientInvitationChecker $clientInvitationChecker,
        private SendInvitationToClientEmail $sendInvitationToClientEmail,
        private InvitationRepository $invitationRepository,
    ) {
    }

    /**
     * @throws ClientAlreadyInvitedException
     * @throws InvalidLoyaltyProgramException
     */
    public function __invoke(InviteClientToLoyaltyProgramCommand $command): void
    {
        $invitation = Invitation::generateNew(
            $command->partner,
            $command->loyaltyProgram,
            $command->client,
            $this->clientInvitationChecker,
        );
        $invitation->sendEmailToUser($this->sendInvitationToClientEmail);
        $this->invitationRepository->save($invitation);
    }
}