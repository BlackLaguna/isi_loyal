<?php

declare(strict_types=1);

namespace ClientPurchases\Infrastructure\Controller;

use ClientPurchases\Application\CQRS\Command\AddClientPurchasesCommand;
use ClientPurchases\Application\CQRS\Query\GetClientLoyaltyProgramStatisticsQuery;
use ClientPurchases\Domain\Client;
use ClientPurchases\Domain\LoyaltyProgram;
use ClientPurchases\Domain\Partner;
use ClientPurchases\Domain\ValueFactor;
use ClientPurchases\Infrastructure\Controller\ArgumentResolver\ClientValueResolver;
use ClientPurchases\Infrastructure\Controller\ArgumentResolver\LoyaltyProgramValueResolver;
use ClientPurchases\Infrastructure\Controller\ArgumentResolver\PartnerValueResolver;
use ClientPurchases\Infrastructure\Controller\Request\CreateClientPurchasesRequest;
use SharedKernel\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class ClientPurchasesController extends AbstractController
{
    #[Route(
        path: '/loyalty_programs/{loyalty_program_uuid}/clients/{client_email}/clients_purchases',
        name: 'create_clients_purchases',
        methods: ['POST'],
    )]
    public function createLoyaltyProgram(
        #[MapRequestPayload(acceptFormat: 'json')] CreateClientPurchasesRequest $request,
        #[ValueResolver(PartnerValueResolver::class)] Partner $partner,
        #[ValueResolver(LoyaltyProgramValueResolver::class)] LoyaltyProgram $loyaltyProgram,
        #[ValueResolver(ClientValueResolver::class)] Client $client,
        MessageBusInterface $commandBus,
    ): JsonResponse {
        if (!$loyaltyProgram->isAssignedToPartner($partner)) {
            throw new NotFoundHttpException();
        }

        $commandBus->dispatch(new AddClientPurchasesCommand(
            $client,
            $loyaltyProgram,
            ValueFactor::createFromInt($request->valueFactor)
        ));

        return new JsonResponse(status: Response::HTTP_CREATED);
    }

    #[Route(
        path: '/loyalty_programs/{loyalty_program_uuid}/clients/{client_email}',
        name: 'get_loyalty_program_clients_statistics',
        methods: ['GET'],
    )]
    public function getClientStatistics(
        #[ValueResolver(PartnerValueResolver::class)] Partner $partner,
        #[ValueResolver(LoyaltyProgramValueResolver::class)] LoyaltyProgram $loyaltyProgram,
        #[ValueResolver(ClientValueResolver::class)] Client $client,
        QueryBus $queryBus,
    ): JsonResponse {
        if (!$loyaltyProgram->isAssignedToPartner($partner)) {
            throw new NotFoundHttpException();
        }

        $result = $queryBus->dispatch(new GetClientLoyaltyProgramStatisticsQuery(
            $client,
            $loyaltyProgram,
        ));

        return new JsonResponse(['clientStatistic' => $result], status: Response::HTTP_CREATED);
    }
}