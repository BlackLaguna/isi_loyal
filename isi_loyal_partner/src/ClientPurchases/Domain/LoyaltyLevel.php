<?php

declare(strict_types=1);

namespace ClientPurchases\Domain;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\AbstractUid;

#[ORM\Entity]
#[ORM\Table(name: 'loyalty_levels')]
class LoyaltyLevel
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    public AbstractUid $id;

    #[ORM\ManyToOne(targetEntity: LoyaltyProgram::class, inversedBy: 'loyaltyProgramLevels')]
    public LoyaltyProgram $loyaltyProgram;

    #[ORM\Embedded(class: ValueFactor::class, columnPrefix: false)]
    public ValueFactor $valueFactor;
}