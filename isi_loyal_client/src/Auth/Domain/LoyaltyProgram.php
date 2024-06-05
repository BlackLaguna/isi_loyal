<?php

declare(strict_types=1);

namespace Auth\Domain;

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

    #[ORM\ManyToMany(targetEntity: Client::class, cascade: ['PERSIST'])]
    #[ORM\JoinTable(name: 'loyalty_program_client')]
    #[ORM\InverseJoinColumn(name: 'client_id', referencedColumnName: 'email')]
    private Collection $clients;

    public function __construct()
    {
        return $this->clients = new ArrayCollection();
    }

    public function addClientToLoyaltyProgram(Client $client): void
    {
        $isClientAlreadyInLoyaltyProgram = $this->clients->exists(
            static function ($key, Client $element) use ($client) {
                return $client->isEqualTo($element);
            }
        );

        if (!$isClientAlreadyInLoyaltyProgram) {
            $this->clients->add($client);
        }
    }
}