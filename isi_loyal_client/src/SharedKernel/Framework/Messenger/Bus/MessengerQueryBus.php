<?php

declare(strict_types=1);

namespace SharedKernel\Framework\Messenger\Bus;

use SharedKernel\Application\Bus\QueryBus;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerQueryBus implements QueryBus
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    public function dispatch(object $object): mixed
    {
        return $this->handle($object);
    }
}
