<?php

declare(strict_types=1);

namespace Auth\Domain;

use Auth\Domain\Exception\UserNotFoundException;

interface ClientRepository
{
    /** @throws UserNotFoundException */
    public function getUserByEmail(string $email): Client;

    /** @throws UserNotFoundException */
    public function getUserByEmailCanonical(string $emailCanonical): Client;

    public function save(Client $user): void;
}