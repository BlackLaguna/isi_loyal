<?php

declare(strict_types=1);

namespace ClientPurchases\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'loyalty_programs')]
class LoyaltyProgram
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    public Uuid $id;

    #[ORM\ManyToOne(targetEntity: Partner::class)]
    #[ORM\JoinColumn(name: 'partner_id', referencedColumnName: 'email', nullable: false)]
    private Partner $partner;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\OneToMany(mappedBy: 'loyaltyProgram', targetEntity: LoyaltyLevel::class)]
    private Collection $loyaltyProgramLevels;

    public function __construct()
    {
        $this->loyaltyProgramLevels = new ArrayCollection();
    }

    public function isAssignedToPartner(Partner $partner): bool
    {
        return $this->partner->isEqual($partner);
    }

    public function getSortedLoyaltyLevels(): Collection
    {
        $loyaltyLevelIterator = $this->loyaltyProgramLevels->getIterator();
        $loyaltyLevelIterator->uasort(function($a, $b) {
            return $a->valueFactor <=> $b->valueFactor;
        });
        $this->loyaltyProgramLevels = new ArrayCollection(iterator_to_array($loyaltyLevelIterator));

        return $this->loyaltyProgramLevels;
    }

    public function getJsonData(): array
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
        ];
    }
}