<?php

declare(strict_types=1);

namespace LoyaltyProgram\Application\CQRS\Query\View;

use JsonSerializable;
use LoyaltyProgram\Domain\LoyaltyProgram;

final readonly class GetLoyaltyProgramsView implements JsonSerializable
{
    /** @var LoyaltyProgram[] $loyaltyPrograms */
    public function __construct(public array $loyaltyPrograms)
    {
    }

    public function jsonSerialize(): array
    {
        $result = [];

        foreach ($this->loyaltyPrograms as $loyaltyProgram) {
            $result[] = $loyaltyProgram->jsonSerialize();
        }

        return $result;
    }
}