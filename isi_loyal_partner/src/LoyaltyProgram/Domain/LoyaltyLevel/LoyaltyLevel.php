<?php

declare(strict_types=1);

namespace LoyaltyProgram\Domain\LoyaltyLevel;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use LoyaltyProgram\Domain\LoyaltyProgram;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\AbstractUid;

#[ORM\Entity]
#[ORM\Table(name: 'loyalty_levels')]
class LoyaltyLevel implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: 'uuid', unique: true)]
    public AbstractUid $id;

    #[ORM\ManyToOne(targetEntity: LoyaltyProgram::class, inversedBy: 'loyaltyProgramLevels')]
    public ?LoyaltyProgram $loyaltyProgram;

    #[ORM\Embedded(class: ValueFactor::class, columnPrefix: false)]
    public ValueFactor $valueFactor;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    public string $name;

    public function __construct(string $loyaltyLevelName)
    {
        $this->name = $loyaltyLevelName;
    }

    public function isHaveThisId(AbstractUid $id): bool
    {
        return $this->id->equals($id);
    }

    public function isEqual(self $loyaltyLevel): bool
    {
        return $this->name === $loyaltyLevel->name
            && $this->valueFactor->isEqual($loyaltyLevel->valueFactor);
    }

    public function setValueFactor(ValueFactor $valueFactor): void
    {
        $this->valueFactor = $valueFactor;
    }

    public function assignLoyaltyProgram(LoyaltyProgram $loyaltyProgram): void
    {
        $this->loyaltyProgram = $loyaltyProgram;
    }

    public function updateData(ValueFactor $valueFactor, string $name): void
    {
        $this->valueFactor = $valueFactor;
        $this->name = $name;
    }

    public function unassignLoyaltyProgram(): void
    {
        $this->loyaltyProgram = null;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'valueFactor' => $this->valueFactor,
            'loyaltyProgram' => $this->loyaltyProgram->id,
        ];
    }
}