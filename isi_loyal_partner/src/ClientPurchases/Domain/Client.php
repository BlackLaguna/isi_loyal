<?php

namespace ClientPurchases\Domain;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'clients')]
class Client
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private ?string $email;

    public function getEmail(): ?string
    {
        return $this->email;
    }
}