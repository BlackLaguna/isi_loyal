<?php

declare(strict_types=1);

namespace LoyaltyProgram\Infrastructure\Service;

use LoyaltyProgram\Domain\LoyaltyProgramRepository;
use LoyaltyProgram\Domain\Partner;
use LoyaltyProgram\Domain\Service\IsPartnerAlreadyHasLoyaltyProgramWithSameName;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class DbalIsPartnerAlreadyHasLoyaltyProgramWithSameName implements IsPartnerAlreadyHasLoyaltyProgramWithSameName
{
    public function __construct(private LoyaltyProgramRepository $repository)
    {
    }

    public function __invoke(Partner $partner, string $loyaltyProgramName): bool
    {
        try {
            $this->repository->findByPartnerAndName($partner, $loyaltyProgramName);
        } catch (NotFoundHttpException) {
            return false;
        }

        return true;
    }
}