<?php

declare(strict_types=1);

namespace LoyaltyProgram\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use LoyaltyProgram\Domain\Exception\LoyaltyLevelAlreadyExists;
use LoyaltyProgram\Domain\LoyaltyLevel\LoyaltyLevel;
use LoyaltyProgram\Domain\LoyaltyLevel\ValueFactor;
use LoyaltyProgram\Domain\Service\IsPartnerAlreadyHasLoyaltyProgramWithSameName;
use LoyaltyProgram\Domain\Service\RecalculateClientsLoyaltyLevels;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\AbstractUid;

#[ORM\Entity]
#[ORM\Table(name: 'loyalty_programs')]
class LoyaltyProgram implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: 'uuid', unique: true)]
    public AbstractUid $id;

    #[ORM\ManyToOne(targetEntity: Partner::class, cascade: ['PERSIST', 'REMOVE'])]
    #[ORM\JoinColumn(name: 'partner_id', referencedColumnName: 'email', nullable: false)]
    private Partner $partner;

    #[ORM\OneToMany(mappedBy: 'loyaltyProgram', targetEntity: LoyaltyLevel::class, cascade: ['PERSIST', 'REMOVE'])]
    private Collection $loyaltyProgramLevels;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    public function __construct(Partner $partner, string $name)
    {
        $this->loyaltyProgramLevels = new ArrayCollection();
        $this->partner = $partner;
        $this->name = $name;
    }

    public static function createNew(
        Partner $partner,
        string $name,
        IsPartnerAlreadyHasLoyaltyProgramWithSameName $alreadyHasLoyaltyProgramWithSameName,
    ): self {
        if (!($alreadyHasLoyaltyProgramWithSameName)($partner, $name)) {
            return new self($partner, $name);
        } else {
            throw new LoyaltyLevelAlreadyExists();
        }
    }

    /** @throws LoyaltyLevelAlreadyExists */
    public function addLoyaltyLevel(
        ValueFactor $valueFactor,
        RecalculateClientsLoyaltyLevels $recalculateClientsLoyaltyLevels
    ): void {
        $loyaltyLevel = new LoyaltyLevel();
        $loyaltyLevel->setValueFactor($valueFactor);

        $isLoyaltyLevelAlreadyExists = $this->loyaltyProgramLevels->exists(
            static function (int $key, LoyaltyLevel $loyaltyLevelEntry) use ($loyaltyLevel) {
                return $loyaltyLevel->isEqual($loyaltyLevelEntry);
        });

        if ($isLoyaltyLevelAlreadyExists) {
            throw new LoyaltyLevelAlreadyExists();
        }

        $this->loyaltyProgramLevels->add($loyaltyLevel);
    }

    public function isOwnedBy(Partner $partner): bool
    {
        return $this->partner->isEqual($partner);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
        ];
    }
}