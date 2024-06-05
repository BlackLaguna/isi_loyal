<?php

declare(strict_types=1);

namespace Invitation\Domain;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\AbstractUid;

#[ORM\Entity]
#[ORM\Table(name: 'loyalty_programs')]
class LoyaltyProgram
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    public AbstractUid $id;

    #[ORM\ManyToOne(targetEntity: Partner::class)]
    #[ORM\JoinColumn(name: 'partner_id', referencedColumnName: 'email', nullable: false)]
    private Partner $partner;

    public function isAssignedToPartner(Partner $partner): bool
    {
        return $this->partner->isEqual($partner);
    }
}