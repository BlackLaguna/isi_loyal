<?php

declare(strict_types=1);

namespace LoyaltyProgram\Infrastructure\Controller\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateLoyaltyProgramRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public string $loyaltyProgramName,
    ) {
    }
}