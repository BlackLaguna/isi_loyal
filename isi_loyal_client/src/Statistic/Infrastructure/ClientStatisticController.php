<?php

declare(strict_types=1);

namespace Statistic\Infrastructure;

use Auth\Domain\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Attribute\Route;

class ClientStatisticController extends AbstractController
{
    #[Route(path: '/api/statistics', name: 'get_client_statistics', methods: ['GET'])]
    public function getStatistics(
        #[ValueResolver(ClientValueResolver::class)] Client $client,
        GetClientStatisticQuery $getClientStatisticQuery,
    ): JsonResponse {
        return new JsonResponse(['statistic' => ($getClientStatisticQuery)($client->getUserIdentifier())]);
    }
}