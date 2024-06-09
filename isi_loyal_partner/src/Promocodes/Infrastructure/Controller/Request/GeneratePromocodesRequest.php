<?php

declare(strict_types=1);

namespace Promocodes\Infrastructure\Controller\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class GeneratePromocodesRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Choice(choices: ['percentage', 'integer'])]
        public string $type,
        #[Assert\NotBlank]
        #[Assert\Positive]
        public int $valueFactor,
    ) {
    }
}