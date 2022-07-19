<?php

declare(strict_types=1);

namespace App\Domain\MovieOwnersDecorator;

use App\Domain\Message\AddMovieCommand;
use App\Domain\Message\FailedToAddMovieEvent;
use App\Domain\Message\GetMovieByNameQuery;
use App\Domain\Message\GetMoviesQuery;
use App\Domain\Message\MovieMessageInterface;
use App\Domain\MovieInterface;
use App\Domain\MovieOwnerRepositoryInterface;
use App\Domain\MovieOwnersInterface;

final class MovieOwnersAddExistingMovieDecorator implements MovieOwnersInterface
{
    private MovieOwnersInterface $decorated;
    private MovieOwnerRepositoryInterface $repository;

    public function __construct(
        MovieOwnersInterface $decorated,
        MovieOwnerRepositoryInterface $repository
    )
    {
        $this->decorated = $decorated;
        $this->repository = $repository;
    }

    public function addMovie(AddMovieCommand $command): MovieMessageInterface
    {
        return $this->movieExists($command)
            ? FailedToAddMovieEvent::fromCommand($command)
            : $this->decorated->addMovie($command);
    }

    private function movieExists(AddMovieCommand $command): bool
    {
        return $this->repository
            ->getMovieOwner(
                $command->getMovieOwnerId()
            )->hasMovie(
                $command->getName()
            );
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
