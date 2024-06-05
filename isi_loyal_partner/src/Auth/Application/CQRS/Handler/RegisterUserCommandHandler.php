<?php

declare(strict_types=1);

namespace Auth\Application\CQRS\Handler;

use Auth\Application\CQRS\Command\RegisterUserCommand;
use Auth\Domain\Exception\UserAlreadyExist;
use Auth\Domain\Service\CheckIfEmailAlreadyRegistered;
use Auth\Domain\Partner;
use Auth\Domain\PartnerRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsMessageHandler]
readonly class RegisterUserCommandHandler
{
    public function __construct(
        private PartnerRepository             $userRepository,
        private CheckIfEmailAlreadyRegistered $checkIfEmailAlreadyRegistered,
        private UserPasswordHasherInterface   $passwordHasher,
    ){
    }

    /** @throws UserAlreadyExist */
    public function __invoke(RegisterUserCommand $command): void
    {
        $user = Partner::createNewForRegistration(
            $this->checkIfEmailAlreadyRegistered,
            $command->email,
            $command->password,
        );
        $user->hashPassword($this->passwordHasher);

        $this->userRepository->save($user);
    }
}