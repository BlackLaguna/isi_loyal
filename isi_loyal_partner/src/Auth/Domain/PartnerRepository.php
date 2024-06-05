<?php

declare(strict_types=1);

namespace Auth\Domain;

use Auth\Domain\Exception\UserNotFoundException;

interface PartnerRepository
{
    /** @throws UserNotFoundException */
    public function getUserByEmail(string $email): Partner;

    /** @throws UserNotFoundException */
    public function getUserByEmailCanonical(string $emailCanonical): Partner;

    public function save(Partner $user): void;
}