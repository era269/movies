<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Message\AddMovieCommand;
use App\Domain\Message\GetMovieByNameQuery;
use App\Domain\Message\GetMoviesQuery;
use App\Domain\Message\MovieAddedEvent;
use Psr\EventDispatcher\EventDispatcherInterface;

final class MovieOwners
{
    private MovieOwnerRepositoryInterface $ownerRepository;
    private EventDispatcherInterface $eventDispatcher;
    private MovieFactoryInterface $movieFactory;

    public function __construct(
        MovieOwnerRepositoryInterface $ownerRepository,
        EventDispatcherInterface $eventDispatcher,
        MovieFactoryInterface $movieFactory
    )
    {
        $this->ownerRepository = $ownerRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->movieFactory = $movieFactory;
    }

    public function addMovie(AddMovieCommand $command): MovieAddedEvent
    {
        $this->ownerRepository->getMovieOwner(
            $command->getMovieOwnerId()
        )->addMovie(
            $this->movieFactory->create($command)
        );
        $event = $this->createEvent($command);
        $this->eventDispatcher->dispatch($event);
        return $event;
    }

    public function getMovie(GetMovieByNameQuery $query): MovieInterface
    {
        return $this->ownerRepository
            ->getMovieOwner($query->getId())
            ->getMovieByName($query->getName());
    }

    /**
     * @return MovieInterface[]
     */
    public function getMovies(GetMoviesQuery $query): iterable
    {
        return $this->ownerRepository
            ->getMovieOwner($query->getId())
            ->getMovies();
    }

    private function createEvent(AddMovieCommand $addUserMovieCommand): MovieAddedEvent
    {
        return new MovieAddedEvent(
            $addUserMovieCommand->getMovieOwnerId(),
            $addUserMovieCommand->getName(),
            $addUserMovieCommand->getCasts(),
            $addUserMovieCommand->getReleaseDate(),
            $addUserMovieCommand->getDirector(),
            $addUserMovieCommand->getRatings()
        );
    }
}
