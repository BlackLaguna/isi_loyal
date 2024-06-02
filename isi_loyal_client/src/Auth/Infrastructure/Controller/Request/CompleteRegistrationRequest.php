<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Controller\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CompleteRegistrationRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(
            min: 8,
            max: 255,
            minMessage: 'Password must has min 8 symbols length',
            maxMessage: 'Password must has max 255 symbols length',
        )]
        public string $password,
        #[Assert\NotBlank]
        #[Assert\EqualTo(propertyPath: 'password')]
        public string $repeatedPassword,
    ) {
    }
}