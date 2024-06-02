<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Controller;

use Auth\Application\CQRS\Command\AcceptInvitationCommand;
use Auth\Application\CQRS\Command\CompleteUserRegistrationCommand;
use Auth\Application\CQRS\Query\GetUserQuery;
use Auth\Domain\Client;
use Auth\Domain\Invitation;
use Auth\Infrastructure\Controller\ArgumentResolver\ClientValueResolver;
use Auth\Infrastructure\Controller\ArgumentResolver\InvitationValueResolver;
use Auth\Infrastructure\Controller\Request\CompleteRegistrationRequest;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use SharedKernel\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class AuthController extends AbstractController
{
    #[Route('/clients/{client_email}/completeRegistration', name: 'register', methods: ['POST'])]
    public function register(
        #[MapRequestPayload(acceptFormat: 'json')] CompleteRegistrationRequest $request,
        #[ValueResolver(ClientValueResolver::class)] Client $client,
        MessageBusInterface $commandBus,
        QueryBus $queryBus,
        JWTTokenManagerInterface $jwtManager
    ): JsonResponse {
        $commandBus->dispatch(new CompleteUserRegistrationCommand($client, $request->password));
        $user = $queryBus->dispatch(new GetUserQuery($client->getUserIdentifier()));

        return new JsonResponse(['token' => $jwtManager->create($user)], Response::HTTP_CREATED);
    }

    #[Route('/invitations/{invitation_uuid}/accept', name: 'accept_invitation', methods: ['POST'])]
    public function acceptInvitation(
        #[ValueResolver(InvitationValueResolver::class)] Invitation $invitation,
        MessageBusInterface $commandBus,
        QueryBus $queryBus,
        JWTTokenManagerInterface $jwtManager
    ): JsonResponse {
        $commandBus->dispatch(new AcceptInvitationCommand($invitation));
        /** @var Client $client */
        $client = $queryBus->dispatch(new GetUserQuery($invitation->getClientEmail()->email));

        if ($client->isMustToCompleteRegistration()) {
            return new JsonResponse(['clientEmail' => $client->getUserIdentifier()], Response::HTTP_CREATED);
        } else {
            return new JsonResponse(['token' => $jwtManager->create($client)], Response::HTTP_OK);
        }
    }
}