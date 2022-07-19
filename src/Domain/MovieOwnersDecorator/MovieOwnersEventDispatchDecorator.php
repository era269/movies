<?php

declare(strict_types=1);

namespace App\Domain\MovieOwnersDecorator;

use App\Domain\Message\AddMovieCommand;
use App\Domain\Message\GetMovieByNameQuery;
use App\Domain\Message\GetMoviesQuery;
use App\Domain\Message\MovieMessageInterface;
use App\Domain\MovieInterface;
use App\Domain\MovieOwnersInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

final class MovieOwnersEventDispatchDecorator implements MovieOwnersInterface
{
    private MovieOwnersInterface $decorated;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        MovieOwnersInterface $decorated,
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

    public function getMovie(GetMovieByNameQuery $query): MovieInterface
    {
        return $this->decorated->getMovie($query);
    }

    /**
     * @inheritDoc
     */
    public function getMovies(GetMoviesQuery $query): iterable
    {
        return $this->decorated->getMovies($query);
    }
}
