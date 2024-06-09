<?php

declare(strict_types=1);

namespace ClientPurchases\Infrastructure\Controller\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateClientPurchasesRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        public int $valueFactor,
    ) {
    }
}