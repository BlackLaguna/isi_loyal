<?php

declare(strict_types=1);

namespace Promocodes\Infrastructure\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Invitation\Domain\Partner;
use Invitation\Infrastructure\Controller\ArgumentResolver\PartnerValueResolver;
use Promocodes\Domain\Promocode;
use Promocodes\Infrastructure\Controller\Request\GeneratePromocodesRequest;
use Promocodes\Infrastructure\Query\DbalGetClientsIdsQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class PromocodesController extends AbstractController
{
    #[Route(
        path: '/api/loyalty_programs/{loyaltyProgramUuid}/loyalty_levels/{loyaltyLevelUuid}/promocodes',
        name: 'generate_promocodes',
        methods: ['POST']
    )]
    public function getStatistics(
        #[MapRequestPayload(acceptFormat: 'json')] GeneratePromocodesRequest $request,
        string $loyaltyProgramUuid,
        string $loyaltyLevelUuid,
        #[ValueResolver(PartnerValueResolver::class)] Partner $partner,
        DbalGetClientsIdsQuery $getClientsIds,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $clientIds = ($getClientsIds)(
            Uuid::fromString($loyaltyProgramUuid),
            Uuid::fromString($loyaltyLevelUuid),
        );

        foreach ($clientIds as $clientId) {

            $promocode = new Promocode($clientId['client_id'], Uuid::fromString($loyaltyProgramUuid), $request->type, $request->valueFactor);
            $entityManager->persist($promocode);
        }

        $entityManager->flush();

        return new JsonResponse(status: Response::HTTP_CREATED);
    }

    #[Route(
        path: '/api/loyalty_programs/{loyaltyProgramUuid}/promocodes/{promocodeUuid}',
        name: 'get_promocode',
        methods: ['GET']
    )]
    public function generatePromocodes(
        string $loyaltyProgramUuid,
        string $promocodeUuid,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $record = $entityManager->getRepository(Promocode::class)->findOneBy(
            ['id' => $promocodeUuid, 'loyaltyProgramId' => $loyaltyProgramUuid],
        );

        if (empty($record)) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse(['promocode' => $record], status: Response::HTTP_OK);
    }

    #[Route(
        path: '/api/loyalty_programs/{loyaltyProgramUuid}/promocodes/{promocodeUuid}',
        name: 'delete_promocode',
        methods: ['DELETE']
    )]
    public function invalidatePromocode(
        string $loyaltyProgramUuid,
        string $promocodeUuid,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $record = $entityManager->getRepository(Promocode::class)->findOneBy(
            ['id' => $promocodeUuid, 'loyaltyProgramId' => $loyaltyProgramUuid],
        );

        if (empty($record)) {
            throw new NotFoundHttpException();
        }

        $entityManager->remove($record);
        $entityManager->flush();

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}