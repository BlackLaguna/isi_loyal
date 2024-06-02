<?php

declare(strict_types=1);

namespace Auth\Application\CQRS\Command;

final readonly class RegisterUserCommand
{
    public function __construct(
        public string $email,
        public string $password,
    ) {
    }
}