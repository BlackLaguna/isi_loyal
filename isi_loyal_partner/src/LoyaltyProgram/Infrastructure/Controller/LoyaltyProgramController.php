<?php

declare(strict_types=1);

namespace LoyaltyProgram\Infrastructure\Controller;

use LoyaltyProgram\Application\CQRS\Command\AddLoyaltyLevelToLoyaltyProgramCommand;
use LoyaltyProgram\Application\CQRS\Command\CreateLoyaltyProgramCommand;
use LoyaltyProgram\Application\CQRS\Query\GetLoyaltyProgramQuery;
use LoyaltyProgram\Domain\LoyaltyProgram;
use LoyaltyProgram\Domain\Partner;
use LoyaltyProgram\Infrastructure\Controller\ArgumentResolver\LoyaltyProgramValueResolver;
use LoyaltyProgram\Infrastructure\Controller\ArgumentResolver\PartnerValueResolver;
use LoyaltyProgram\Infrastructure\Controller\Request\AddLoyaltyLevelRequest;
use LoyaltyProgram\Infrastructure\Controller\Request\CreateLoyaltyProgramRequest;
use SharedKernel\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class LoyaltyProgramController extends AbstractController
{
    #[Route('/loyalty_programs', name: 'create_loyalty_programs', methods: ['POST'])]
    public function createLoyaltyProgram(
        #[MapRequestPayload(acceptFormat: 'json')] CreateLoyaltyProgramRequest $request,
        #[ValueResolver(PartnerValueResolver::class)] Partner $partner,
        MessageBusInterface $commandBus,
        QueryBus $queryBus,
    ): JsonResponse {
        $commandBus->dispatch(new CreateLoyaltyProgramCommand($partner, $request->loyaltyProgramName));
        /** @var LoyaltyProgram $loyaltyProgram */
        $loyaltyProgram = $queryBus->dispatch(new GetLoyaltyProgramQuery($partner, $request->loyaltyProgramName));

        return new JsonResponse(['loyaltyProgram' => $loyaltyProgram], Response::HTTP_CREATED);
    }

    #[Route('/loyalty_programs/{loyalty_program_uuid}/loyalty_program_levels', name: 'add_loyalty_level', methods: ['POST'])]
    public function add(
        #[MapRequestPayload(acceptFormat: 'json')] AddLoyaltyLevelRequest $request,
        #[ValueResolver(PartnerValueResolver::class)] Partner $partner,
        #[ValueResolver(LoyaltyProgramValueResolver::class)] LoyaltyProgram $loyaltyProgram,
        MessageBusInterface $commandBus,
    ): JsonResponse {
        if (!$loyaltyProgram->isOwnedBy($partner)) {
            throw $this->createNotFoundException();
        }

        $commandBus->dispatch(new AddLoyaltyLevelToLoyaltyProgramCommand(
            $loyaltyProgram,
            $request->loyaltyLevelName,
            $request->valueFactor,
        ));

        return new JsonResponse(['loyaltyProgram' => $loyaltyProgram], Response::HTTP_CREATED);
    }
}