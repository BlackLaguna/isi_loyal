<?php

declare(strict_types=1);

namespace Auth\Domain\Service;

interface CheckIfEmailAlreadyRegistered
{
    public function __invoke(string $email): bool;
}