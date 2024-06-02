<?php

declare(strict_types=1);

namespace Auth\Application\CQRS\Query;

final readonly class GetUserQuery
{
    public function __construct(public string $email)
    {
    }
}