<?php

declare(strict_types=1);

namespace Invitation\Infrastructure\Controller;

use Invitation\Application\CQRS\Command\InviteClientToLoyaltyProgramCommand;
use Invitation\Domain\ClientEmail;
use Invitation\Domain\LoyaltyProgram;
use Invitation\Domain\Partner;
use Invitation\Infrastructure\Controller\ArgumentResolver\LoyaltyProgramValueResolver;
use Invitation\Infrastructure\Controller\ArgumentResolver\PartnerValueResolver;
use Invitation\Infrastructure\Controller\Request\InviteClientRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class InvitationController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route(path: '/loyalty_programs/{loyalty_program_uuid}/invitations', name: 'create_invitation', methods: ['POST'])]
    public function inviteClientToLoyaltyProgram(
        #[MapRequestPayload(acceptFormat: 'json')] InviteClientRequest $request,
        #[ValueResolver(PartnerValueResolver::class)] Partner $partner,
        #[ValueResolver(LoyaltyProgramValueResolver::class)] LoyaltyProgram $loyaltyProgram,
        MessageBusInterface $commandBus,
    ): JsonResponse {
        $commandBus->dispatch(
            new InviteClientToLoyaltyProgramCommand(
                $partner,
                $loyaltyProgram,
                new ClientEmail($request->clientEmail),
            )
        );

        return new JsonResponse([]);
    }

    #[Route(path: '/test', name: 'test', methods: ['POST'])]
    public function test(Request $request): JsonResponse
    {
        return new JsonResponse($request->toArray());
    }
}