<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Service;

use Auth\Domain\Exception\UserNotFoundException;
use Auth\Domain\Service\CheckIfEmailAlreadyRegistered;
use Auth\Domain\ClientRepository;

final readonly class DbalCheckIfEmailAlreadyRegistered implements CheckIfEmailAlreadyRegistered
{
    public function __construct(private ClientRepository $userRepository)
    {
    }

    public function __invoke(string $email): bool
    {
        try {
            $this->userRepository->getUserByEmailCanonical($email);

            return true;
        } catch (UserNotFoundException) {
            return false;
        }
    }
}