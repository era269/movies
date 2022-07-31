<?php

declare(strict_types=1);

namespace App\EventDispatcher;

use Doctrine\Persistence\ObjectManager;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;

final class MovieLibraryPersistenceEventDispatcherDecorator implements EventDispatcherInterface
{
    public function __construct(private readonly EventDispatcherInterface $eventDispatcher, private readonly ObjectManager            $om)
    {
    }

    /**
     * @inheritDoc
     */
    public function dispatch(object $event): object
    {
        $this->om->flush();
        return $this->eventDispatcher->dispatch($event);
    }

    /**
     * for tests
     * ToDo: figure out how to solve test issues without touching prod code
     */
    public function getCalledListeners(Request $request = null): array
    {
        return $this->eventDispatcher instanceof TraceableEventDispatcher
            ? $this->eventDispatcher->getCalledListeners($request)
            : [];
    }
}
