<?php

declare(strict_types=1);

namespace Invitation\Domain;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'partners')]
class Partner
{
    #[ORM\Id]
    #[ORM\Column(length: 180, unique: true)]
    public ?string $email;

    public function isEqual(Partner $partner): bool
    {
        return $this->email === $partner->email;
    }
}