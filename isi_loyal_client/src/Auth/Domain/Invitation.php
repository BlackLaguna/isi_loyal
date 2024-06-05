<?php

declare(strict_types=1);

namespace Auth\Domain;

use Auth\Domain\Exception\UserNotFoundException;
use Auth\Domain\Service\CheckIfEmailAlreadyRegistered;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Uid\AbstractUid;

#[ORM\Entity]
#[ORM\Table(name: 'invitations')]
class Invitation
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private AbstractUid $uuid;

    #[ORM\Column(type: 'enum_invitation_status', enumType: InvitationStatus::class)]
    private InvitationStatus $invitationStatus;

    #[ORM\ManyToOne(targetEntity: LoyaltyProgram::class)]
    #[JoinColumn(name: 'loyalty_program_id', referencedColumnName: 'id')]
    private LoyaltyProgram $loyaltyProgram;

    #[Embedded(class: ClientEmail::class, columnPrefix: false)]
    private ClientEmail $clientEmail;

    /** @throws UserNotFoundException */
    public function acceptInvitation(
        CheckIfEmailAlreadyRegistered $checkIfEmailAlreadyRegistered,
        ClientRepository $clientRepository
    ): void {
        if (($checkIfEmailAlreadyRegistered)($this->clientEmail->email)) {
            $client = $clientRepository->getUserByEmail($this->clientEmail->email);
        } else {
            $client = new Client($this->clientEmail->email);
        }

        $this->invitationStatus = InvitationStatus::ACCEPTED;
        $this->loyaltyProgram->addClientToLoyaltyProgram($client);
    }

    public function getClientEmail(): ClientEmail
    {
        return $this->clientEmail;
    }
}