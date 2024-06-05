<?php

declare(strict_types=1);

namespace Auth\Domain;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
readonly class ClientEmail
{
    public function __construct(#[ORM\Column(name: 'client_email', type: 'string', length: 255)] public string $email)
    {
    }
}