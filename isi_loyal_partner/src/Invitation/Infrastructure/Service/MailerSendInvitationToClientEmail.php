<?php

declare(strict_types=1);

namespace Invitation\Infrastructure\Service;

use Invitation\Domain\ClientEmail;
use Invitation\Domain\Service\SendInvitationToClientEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Uid\AbstractUid;
use Twig\Environment;

final class MailerSendInvitationToClientEmail implements SendInvitationToClientEmail
{
    public function __construct(private readonly MailerInterface $mailer, private readonly Environment $twig)
    {
    }
    public function __invoke(ClientEmail $clientEmail, AbstractUid $invitationUuid, string $partnerEmail): void
    {
        $htmlContent = $this->twig->render('invitation.email.html.twig', [
            'invitation_uuid' => (string) $invitationUuid,
        ]);

        $email = (new Email())
            ->from($partnerEmail)
            ->to($clientEmail->email)
            ->subject('You are invited to new loyalty program')
            ->html($htmlContent);

        $this->mailer->send($email);
    }
}