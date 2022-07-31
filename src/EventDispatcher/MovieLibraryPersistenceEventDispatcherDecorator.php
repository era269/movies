<?php

declare(strict_types=1);

namespace App\EventDispatcher;

use Doctrine\Persistence\ObjectManager;
use Psr\EventDispatcher\EventDispatcherInterface;

final class MovieLibraryPersistenceEventDispatcherDecorator implements EventDispatcherInterface
{
    private EventDispatcherInterface $eventDispatcher;
    private ObjectManager $om;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        ObjectManager            $om
    )
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->om = $om;
    }

    /**
     * @inheritDoc
     */
    public function dispatch(object $event): object
    {
        $this->om->flush();
        return $this->eventDispatcher->dispatch($event);
    }
}
