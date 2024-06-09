<?php

declare(strict_types=1);

namespace ClientPurchases\Domain;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\AbstractUid;

#[ORM\Entity]
#[ORM\Table(name: 'client_purchases')]
class ClientPurchase
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: 'uuid', unique: true)]
    private AbstractUid $uuid;

    #[ORM\Column(type: 'datetime', nullable: false)]
    public DateTimeInterface $createdAt;

    #[ORM\Embedded(columnPrefix: false)]
    public ValueFactor $valueFactor;

    #[ORM\ManyToOne(targetEntity: LoyaltyProgramClient::class, inversedBy: 'clientPurchases')]
    #[ORM\JoinColumn(name: 'loyalty_program_client_client_id', referencedColumnName: 'client_id')]
    #[ORM\JoinColumn(name: 'loyalty_program_client_loyalty_program_id', referencedColumnName: 'loyalty_program_id')]
    public LoyaltyProgramClient $clientLoyaltyProgram;

    public function __construct(LoyaltyProgramClient $clientLoyaltyProgram, ValueFactor $valueFactor)
    {
        $this->createdAt = new DateTimeImmutable();
        $this->valueFactor = $valueFactor;
        $this->clientLoyaltyProgram = $clientLoyaltyProgram;
    }
}