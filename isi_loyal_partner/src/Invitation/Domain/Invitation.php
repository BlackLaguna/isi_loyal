<?php

declare(strict_types=1);

namespace Invitation\Domain;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\JoinColumn;
use Invitation\Domain\Exception\ClientAlreadyInvitedException;
use Invitation\Domain\Exception\InvalidLoyaltyProgramException;
use Invitation\Domain\Service\ClientInvitationChecker;
use Invitation\Domain\Service\SendInvitationToClientEmail;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

#[ORM\Entity]
#[ORM\Table(name: 'invitations')]
class Invitation
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: 'uuid', unique: true)]
    private AbstractUid $uuid;

    #[ORM\Column(type: 'enum_invitation_status', enumType: InvitationStatus::class)]
    private InvitationStatus $invitationStatus = InvitationStatus::NEW;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: Partner::class)]
        #[JoinColumn(name: 'partner_id', referencedColumnName: 'email')]
        private Partner $partner,

        #[ORM\ManyToOne(targetEntity: LoyaltyProgram::class)]
        #[JoinColumn(name: 'loyalty_program_id', referencedColumnName: 'id')]
        private LoyaltyProgram $loyaltyProgram,

        #[Embedded(class: ClientEmail::class, columnPrefix: false)]
        private ClientEmail $clientEmail,
    ) {
    }

    /**
     * @throws ClientAlreadyInvitedException
     * @throws InvalidLoyaltyProgramException
     */
    public static function generateNew(
        Partner $partner,
        LoyaltyProgram $loyaltyProgram,
        ClientEmail $clientEmail,
        ClientInvitationChecker $clientInvitationChecker,
    ): self {
        if ($clientInvitationChecker->isClientAlreadyInvited($loyaltyProgram, $clientEmail)) {
            throw new ClientAlreadyInvitedException();
        }

        if (!$loyaltyProgram->isAssignedToPartner($partner)) {
            throw new InvalidLoyaltyProgramException();
        }

        return new self($partner, $loyaltyProgram, $clientEmail);
    }

    public function sendEmailToUser(SendInvitationToClientEmail $sendInvitationToClientEmail): void
    {
        ($sendInvitationToClientEmail)($this->clientEmail, $this->uuid, $this->partner->email);
        $this->invitationStatus = InvitationStatus::SENT;
    }
}