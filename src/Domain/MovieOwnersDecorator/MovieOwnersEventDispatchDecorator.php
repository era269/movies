<?php

declare(strict_types=1);

namespace App\Domain\MovieOwnersDecorator;

use App\Domain\Message\AddMovieCommand;
use App\Domain\Message\MovieMessageInterface;
use App\Domain\MovieOwnersInterface;
use App\Traits\MovieOwnersDecoratorTrait;
use Psr\EventDispatcher\EventDispatcherInterface;

final class MovieOwnersEventDispatchDecorator implements MovieOwnersInterface
{
    use MovieOwnersDecoratorTrait;

    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        MovieOwnersInterface     $decorated,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->decorated = $decorated;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function addMovie(AddMovieCommand $command): MovieMessageInterface
    {
        $event = $this->decorated->addMovie($command);
        $this->eventDispatcher->dispatch($event);

        return $event;
    }
}
