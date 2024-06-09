<?php

declare(strict_types=1);

namespace LoyaltyProgram\Domain;

use Doctrine\ORM\Mapping as ORM;
use LoyaltyProgram\Domain\LoyaltyLevel\LoyaltyLevel;
use LoyaltyProgram\Domain\LoyaltyLevel\ValueFactor;

#[ORM\Entity]
#[ORM\Table(name: 'loyalty_program_client')]
class LoyaltyProgramClient
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Client::class)]
    #[ORM\JoinColumn(name: 'client_id', referencedColumnName: 'email', nullable: false)]
    public Client $client;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: LoyaltyProgram::class)]
    #[ORM\JoinColumn(name: 'loyalty_program_id', referencedColumnName: 'id', nullable: false)]
    public LoyaltyProgram $loyaltyProgram;

    #[ORM\ManyToOne(targetEntity: LoyaltyLevel::class)]
    public ?LoyaltyLevel $loyaltyLevel;

    #[ORM\Embedded(class: ValueFactor::class, columnPrefix: false)]
    public ValueFactor $valueFactor;

    public function updateLoyaltyLevel(?LoyaltyLevel $loyaltyLevel): void
    {
        $this->loyaltyLevel = $loyaltyLevel;
    }
}