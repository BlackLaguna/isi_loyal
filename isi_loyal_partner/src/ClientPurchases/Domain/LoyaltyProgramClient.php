<?php

declare(strict_types=1);

namespace ClientPurchases\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity]
#[ORM\Table(name: 'loyalty_program_client')]
class LoyaltyProgramClient implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Client::class)]
    #[ORM\JoinColumn(name: 'client_id', referencedColumnName: 'email', nullable: false)]
    public Client $client;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: LoyaltyProgram::class)]
    #[ORM\JoinColumn(name: 'loyalty_program_id', referencedColumnName: 'id', nullable: false)]
    public LoyaltyProgram $loyaltyProgram;

    #[ORM\Embedded(class: ValueFactor::class, columnPrefix: false)]
    public ValueFactor $valueFactor;

    #[ORM\ManyToOne(targetEntity: LoyaltyLevel::class)]
    public LoyaltyLevel $loyaltyLevel;

    #[ORM\OneToMany(mappedBy: 'clientLoyaltyProgram', targetEntity: ClientPurchase::class, cascade: ['PERSIST'])]
    public Collection $clientPurchases;

    public function __construct()
    {
        $this->clientPurchases = new ArrayCollection();
    }

    public function generateNewClientPurchases(ValueFactor $valueFactor): void
    {
        $this->valueFactor->increaseBy($valueFactor);
        $this->clientPurchases->add(new ClientPurchase($this, $valueFactor));

        /** @var LoyaltyLevel $loyaltyProgramLevel */
        foreach ($this->loyaltyProgram->getSortedLoyaltyLevels()->getIterator() as $loyaltyProgramLevel) {
            if ($this->valueFactor->isGreaterOrEqual($loyaltyProgramLevel->valueFactor)) {
                $this->loyaltyLevel = $loyaltyProgramLevel;
            }
        }
    }

    public function jsonSerialize(): array
    {
        return [
            'client' => $this->client->getEmail(),
            'loyaltyProgram' => $this->loyaltyProgram->getJsonData(),
            'valueFactor' => $this->valueFactor,
        ];
    }
}