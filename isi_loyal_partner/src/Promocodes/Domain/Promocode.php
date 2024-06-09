<?php

declare(strict_types=1);

namespace Promocodes\Domain;

use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\AbstractUid;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'promocodes')]

class Promocode
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: 'uuid', unique: true)]
    private AbstractUid $id;

    #[ORM\Column(type: 'string')]
    private string $clientId;

    #[ORM\Column(type: 'uuid')]
    private AbstractUid $loyaltyProgramId;

    #[ORM\Column(type: 'string')]
    private string $type;

    #[ORM\Column(type: 'string')]
    private string $status;

    #[ORM\Column(type: 'integer')]
    private int $valueFactor;

    public function __construct(string $clientId, AbstractUid $loyaltyProgramId, string $type, int $valueFactor)
    {
        $this->clientId = $clientId;
        $this->loyaltyProgramId = $loyaltyProgramId;
        $this->type = $type;
        $this->valueFactor = $valueFactor;
        $this->status = 'NEW';
    }
}