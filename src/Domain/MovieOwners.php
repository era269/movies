<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Message\AddMovieCommand;
use App\Domain\Message\FailedToAddMovieEvent;
use App\Domain\Message\GetMovieByNameQuery;
use App\Domain\Message\GetMoviesQuery;
use App\Domain\Message\MovieAddedEvent;
use App\Domain\Message\MovieMessageInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

final class MovieOwners
{
    private MovieOwnerRepositoryInterface $ownerRepository;
    private EventDispatcherInterface $eventDispatcher;
    private MovieFactoryInterface $movieFactory;

    public function __construct(
        MovieOwnerRepositoryInterface $ownerRepository,
        EventDispatcherInterface      $eventDispatcher,
        MovieFactoryInterface         $movieFactory
    )
    {
        $this->ownerRepository = $ownerRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->movieFactory = $movieFactory;
    }

    public function addMovie(AddMovieCommand $command): MovieMessageInterface
    {
        if ($this->movieExists($command)) {
            return $this->dispatchAndReturn(
                FailedToAddMovieEvent::fromCommand($command)
            );
        }

        $this->getMovieOwner($command)->addMovie(
            $this->movieFactory->create($command)
        );

        return $this->dispatchAndReturn(
            MovieAddedEvent::fromCommand($command)
        );
    }

    private function movieExists(AddMovieCommand $command): bool
    {
        return $this->getMovieOwner($command)->hasMovie(
            $command->getName()
        );
    }

    private function getMovieOwner(MovieOwnerIdAwareInterface $message): MovieOwnerInterface
    {
        return $this->ownerRepository->getMovieOwner(
            $message->getMovieOwnerId()
        );
    }

    private function dispatchAndReturn(MovieMessageInterface $message): MovieMessageInterface
    {
        $this->eventDispatcher->dispatch($message);

        return $message;
    }

    public function getMovie(GetMovieByNameQuery $query): MovieInterface
    {
        return $this->getMovieOwner($query)
            ->getMovieByName($query->getName());
    }

    /**
     * @return MovieInterface[]
     */
    public function getMovies(GetMoviesQuery $query): iterable
    {
        return $this->getMovieOwner($query)
            ->getMovies();
    }
}
