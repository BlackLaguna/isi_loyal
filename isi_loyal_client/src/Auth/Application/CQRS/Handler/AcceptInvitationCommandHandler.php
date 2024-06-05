<?php

declare(strict_types=1);

namespace Auth\Application\CQRS\Handler;

use Auth\Application\CQRS\Command\AcceptInvitationCommand;
use Auth\Domain\ClientRepository;
use Auth\Domain\Exception\UserNotFoundException;
use Auth\Domain\Service\CheckIfEmailAlreadyRegistered;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class AcceptInvitationCommandHandler
{
    public function __construct(
        private CheckIfEmailAlreadyRegistered $checkIfEmailAlreadyRegistered,
        private ClientRepository $clientRepository,
    ) {
    }

    /** @throws UserNotFoundException */
    public function __invoke(AcceptInvitationCommand $acceptInvitationCommand): void
    {
        $acceptInvitationCommand->invitation->acceptInvitation(
            $this->checkIfEmailAlreadyRegistered,
            $this->clientRepository
        );
    }
}