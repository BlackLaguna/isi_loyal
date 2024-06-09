<?php

declare(strict_types=1);

namespace LoyaltyProgram\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use LoyaltyProgram\Domain\Exception\LoyaltyLevelAlreadyExists;
use LoyaltyProgram\Domain\Exception\LoyaltyLevelNotExists;
use LoyaltyProgram\Domain\LoyaltyLevel\LoyaltyLevel;
use LoyaltyProgram\Domain\LoyaltyLevel\ValueFactor;
use LoyaltyProgram\Domain\Service\IsPartnerAlreadyHasLoyaltyProgramWithSameName;
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

    #[ORM\OneToMany(mappedBy: 'loyaltyProgram', targetEntity: LoyaltyProgramClient::class, cascade: ['PERSIST', 'REMOVE'])]
    #[ORM\JoinColumn(name: 'loyalty_program_client', nullable: false)]
    private Collection $loyaltyProgramClients;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    public function __construct(Partner $partner, string $name)
    {
        $this->loyaltyProgramLevels = new ArrayCollection();
        $this->loyaltyProgramClients = new ArrayCollection();
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
    public function addNewLoyaltyLevel(
        ValueFactor $valueFactor,
        string $loyaltyLevelName
    ): void {
        $loyaltyLevel = new LoyaltyLevel($loyaltyLevelName);
        $loyaltyLevel->setValueFactor($valueFactor);
        $loyaltyLevel->assignLoyaltyProgram($this);

        $isLoyaltyLevelAlreadyExists = $this->loyaltyProgramLevels->exists(
            static function (int $key, LoyaltyLevel $loyaltyLevelEntry) use ($loyaltyLevel) {
                return $loyaltyLevel->isEqual($loyaltyLevelEntry);
        });

        if ($isLoyaltyLevelAlreadyExists) {
            throw new LoyaltyLevelAlreadyExists();
        }

        $this->loyaltyProgramLevels->add($loyaltyLevel);
        $this->recalculateLoyaltyLevels();
    }

    /**
     * @throws LoyaltyLevelNotExists
     */
    public function editLoyaltyLevel(AbstractUid $loyaltyLevelUuid, ValueFactor $valueFactor, string $loyaltyLevelName): void
    {
        $isLoyaltyLevelExists = $this->loyaltyProgramLevels->exists(
            static function (int $key, LoyaltyLevel $loyaltyLevelEntry) use ($loyaltyLevelUuid) {
                return $loyaltyLevelEntry->isHaveThisId($loyaltyLevelUuid);
            });

        if (!$isLoyaltyLevelExists) {
            throw new LoyaltyLevelNotExists();
        }

        /** @var LoyaltyLevel $loyaltyLevel */
        $loyaltyLevel = $this->loyaltyProgramLevels->filter(
            static function (LoyaltyLevel $loyaltyLevelEntry) use ($loyaltyLevelUuid) {
                return $loyaltyLevelEntry->isHaveThisId($loyaltyLevelUuid);
            }
        )->first();

        $loyaltyLevel->updateData($valueFactor, $loyaltyLevelName);

        $this->recalculateLoyaltyLevels();
    }

    /**
     * @throws LoyaltyLevelNotExists
     */
    public function deleteLoyaltyLevel(AbstractUid $loyaltyLevelUuid): void
    {
        $isLoyaltyLevelExists = $this->loyaltyProgramLevels->exists(
            static function (int $key, LoyaltyLevel $loyaltyLevelEntry) use ($loyaltyLevelUuid) {
                return $loyaltyLevelEntry->isHaveThisId($loyaltyLevelUuid);
        });

        if (!$isLoyaltyLevelExists) {
            throw new LoyaltyLevelNotExists();
        }

        /** @var LoyaltyLevel $loyaltyLevel */
        $loyaltyLevel = $this->loyaltyProgramLevels->filter(
            static function (LoyaltyLevel $loyaltyLevelEntry) use ($loyaltyLevelUuid) {
                return $loyaltyLevelEntry->isHaveThisId($loyaltyLevelUuid);
            }
        )->first();

        $this->loyaltyProgramLevels->removeElement($loyaltyLevel);
        $loyaltyLevel->unassignLoyaltyProgram();
        $this->recalculateLoyaltyLevels();
    }

    private function recalculateLoyaltyLevels(): void
    {
        $loyaltyLevelIterator = $this->loyaltyProgramLevels->getIterator();

        $loyaltyLevelIterator->uasort(function($a, $b) {
            return $a->valueFactor <=> $b->valueFactor;
        });

        $this->loyaltyProgramLevels = new ArrayCollection(iterator_to_array($loyaltyLevelIterator));

        /** @var LoyaltyProgramClient $loyaltyProgramClient */
        foreach ($this->loyaltyProgramClients->getIterator() as $loyaltyProgramClient) {
            $loyaltyProgramClient->updateLoyaltyLevel(null);

            /** @var LoyaltyLevel $loyaltyProgramLevel */
            foreach ($this->loyaltyProgramLevels->getIterator() as $loyaltyProgramLevel) {
                if ($loyaltyProgramClient->valueFactor->isGreaterOrEqual($loyaltyProgramLevel->valueFactor)) {
                    $loyaltyProgramClient->updateLoyaltyLevel($loyaltyProgramLevel);
                }
            }
        }
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
            'loyaltyLevels' => $this->loyaltyProgramLevels->toArray()
        ];
    }
}