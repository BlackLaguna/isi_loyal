<?php

declare(strict_types=1);

namespace Auth\Application\CQRS\Handler;

use Auth\Application\CQRS\Command\CompleteUserRegistrationCommand;
use Auth\Domain\ClientRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsMessageHandler]
readonly class CompleteUserRegistrationCommandHandler
{
    public function __construct(
        private ClientRepository $clientRepository,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function __invoke(CompleteUserRegistrationCommand $command): void
    {
        $command->client->updatePassword($this->passwordHasher, $command->password);
        $this->clientRepository->save($command->client);
    }
}