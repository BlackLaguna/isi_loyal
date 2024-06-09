<?php

declare(strict_types=1);

namespace LoyaltyProgram\Infrastructure\Controller\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class EditLoyaltyLevelRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public string $loyaltyLevelName,
        #[Assert\NotBlank]
        public int $valueFactor,
    ) {
    }
}