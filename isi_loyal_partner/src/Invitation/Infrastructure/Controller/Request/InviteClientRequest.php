<?php

declare(strict_types=1);

namespace Invitation\Infrastructure\Controller\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class InviteClientRequest
{
    public function __construct(
        #[Assert\Email]
        public string $clientEmail,
    ) {
    }
}