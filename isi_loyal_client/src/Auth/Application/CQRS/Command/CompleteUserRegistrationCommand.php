<?php

declare(strict_types=1);

namespace Auth\Application\CQRS\Command;

use Auth\Domain\Client;

final readonly class CompleteUserRegistrationCommand
{
    public function __construct(
        public Client $client,
        public string $password,
    ) {
    }
}