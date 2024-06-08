<?php

declare(strict_types=1);

namespace LoyaltyProgram\Domain\LoyaltyLevel;

use Doctrine\ORM\Mapping as ORM;
use LoyaltyProgram\Domain\LoyaltyProgram;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\AbstractUid;

#[ORM\Entity]
#[ORM\Table(name: 'loyalty_level')]
class LoyaltyLevel
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: 'uuid', unique: true)]
    public AbstractUid $id;

    #[ORM\ManyToOne(targetEntity: LoyaltyProgram::class, cascade: ['PERSIST', 'REMOVE'], inversedBy: 'loyaltyProgramLevels')]
    public LoyaltyProgram $loyaltyProgram;

    #[ORM\Embedded(class: ValueFactor::class)]
    public ValueFactor $valueFactor;

    public function __construct()
    {
    }

    public function isEqual(self $loyaltyLevel): bool
    {
        return $this->id === $loyaltyLevel->id
            && $this->valueFactor->isEqual($loyaltyLevel->valueFactor);
    }

    public function setValueFactor(ValueFactor $valueFactor): void
    {
        $this->valueFactor = $valueFactor;
    }
}