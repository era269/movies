<?php

declare(strict_types=1);

namespace App\Domain\MovieOwnersDecorator;

use App\Domain\Message\AddMovieCommand;
use App\Domain\Message\MovieMessageInterface;
use App\Domain\MovieLibraryInterface;
use App\Traits\MovieOwnersDecoratorTrait;
use Psr\EventDispatcher\EventDispatcherInterface;

final class MovieLibraryEventDispatchDecorator implements MovieLibraryInterface
{
    use MovieOwnersDecoratorTrait;

    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        MovieLibraryInterface    $decorated,
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
