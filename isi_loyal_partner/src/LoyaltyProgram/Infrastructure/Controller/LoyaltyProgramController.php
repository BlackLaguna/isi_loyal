<?php

declare(strict_types=1);

namespace LoyaltyProgram\Infrastructure\Controller;

use LoyaltyProgram\Application\CQRS\Command\AddLoyaltyLevelToLoyaltyProgramCommand;
use LoyaltyProgram\Application\CQRS\Command\CreateLoyaltyProgramCommand;
use LoyaltyProgram\Application\CQRS\Command\DeleteLoyaltyLevelToLoyaltyProgramCommand;
use LoyaltyProgram\Application\CQRS\Command\EditLoyaltyLevelToLoyaltyProgramCommand;
use LoyaltyProgram\Application\CQRS\Query\GetLoyaltyProgramQuery;
use LoyaltyProgram\Application\CQRS\Query\GetLoyaltyProgramsQuery;
use LoyaltyProgram\Domain\LoyaltyProgram;
use LoyaltyProgram\Domain\Partner;
use LoyaltyProgram\Infrastructure\Controller\ArgumentResolver\LoyaltyProgramValueResolver;
use LoyaltyProgram\Infrastructure\Controller\ArgumentResolver\PartnerValueResolver;
use LoyaltyProgram\Infrastructure\Controller\Request\AddLoyaltyLevelRequest;
use LoyaltyProgram\Infrastructure\Controller\Request\CreateLoyaltyProgramRequest;
use LoyaltyProgram\Infrastructure\Controller\Request\EditLoyaltyLevelRequest;
use SharedKernel\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

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

    #[Route('/loyalty_programs', name: 'get_loyalty_level', methods: ['GET'])]
    public function getLoyaltyProgram(
        QueryBus $queryBus,
        #[ValueResolver(PartnerValueResolver::class)] Partner $partner,
    ): JsonResponse {
        $loyaltiesProgramsView = $queryBus->dispatch(new GetLoyaltyProgramsQuery($partner));

        return new JsonResponse(['loyaltyPrograms' => $loyaltiesProgramsView], Response::HTTP_OK);
    }

    #[Route('/loyalty_programs/{loyalty_program_uuid}/loyalty_program_levels', name: 'add_loyalty_level', methods: ['POST'])]
    public function addLoyaltyLevel(
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

    #[Route('/loyalty_programs/{loyalty_program_uuid}/loyalty_program_levels/{loyaltyLevelUuid}', name: 'edit_loyalty_level', methods: ['PUT'])]
    public function editLoyaltyLevel(
        string $loyaltyLevelUuid,
        #[MapRequestPayload(acceptFormat: 'json')] EditLoyaltyLevelRequest $request,
        #[ValueResolver(PartnerValueResolver::class)] Partner $partner,
        #[ValueResolver(LoyaltyProgramValueResolver::class)] LoyaltyProgram $loyaltyProgram,
        MessageBusInterface $commandBus,
    ): JsonResponse {
        if (!$loyaltyProgram->isOwnedBy($partner)) {
            throw $this->createNotFoundException();
        }

        $commandBus->dispatch(new EditLoyaltyLevelToLoyaltyProgramCommand(
            $loyaltyProgram,
            Uuid::fromString($loyaltyLevelUuid),
            $request->loyaltyLevelName,
            $request->valueFactor,
        ));

        return new JsonResponse(status: Response::HTTP_OK);
    }


    #[Route('/loyalty_programs/{loyalty_program_uuid}/loyalty_program_levels/{loyaltyLevelUuid}', name: 'remove_loyalty_level', methods: ['DELETE'])]
    public function deleteLoyaltyLevel(
        string $loyaltyLevelUuid,
        #[ValueResolver(PartnerValueResolver::class)] Partner $partner,
        #[ValueResolver(LoyaltyProgramValueResolver::class)] LoyaltyProgram $loyaltyProgram,
        MessageBusInterface $commandBus,
    ): JsonResponse {
        if (!$loyaltyProgram->isOwnedBy($partner)) {
            throw $this->createNotFoundException();
        }

        $commandBus->dispatch(new DeleteLoyaltyLevelToLoyaltyProgramCommand(
            $loyaltyProgram,
            Uuid::fromString($loyaltyLevelUuid),
        ));

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}