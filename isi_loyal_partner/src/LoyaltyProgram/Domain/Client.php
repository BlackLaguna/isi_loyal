<?php

namespace LoyaltyProgram\Domain;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

#[ORM\Entity]
#[ORM\Table(name: 'clients', indexes: [new Index(columns: ['email'], name: 'idx_email')])]
class Client
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private ?string $email;

    #[ORM\Column(length: 180, unique: false)]
    private ?string $emailCanonical;

    #[ORM\Column]
    private ?string $password;
}