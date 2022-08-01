<?php

declare(strict_types=1);

namespace App\Domain\MovieLibraryDecorator;

use App\Domain\Message\AddMovieCommand;
use App\Domain\Message\MovieMessageInterface;
use App\Domain\MovieLibraryInterface;
use App\Traits\MovieOwnersDecoratorTrait;
use Psr\EventDispatcher\EventDispatcherInterface;

final class MovieLibraryEventDispatchDecorator implements MovieLibraryInterface
{
    use MovieOwnersDecoratorTrait;

    public function __construct(
        MovieLibraryInterface    $decorated,
        private readonly EventDispatcherInterface $eventDispatcher
    )
    {
        $this->decorated = $decorated;
    }

    public function addMovie(AddMovieCommand $command): MovieMessageInterface
    {
        $event = $this->decorated->addMovie($command);
        $this->eventDispatcher->dispatch($event);

        return $event;
    }
}
