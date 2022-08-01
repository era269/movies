<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Message\AddMovieCommand;
use App\Domain\Message\GetMovieByNameQuery;
use App\Domain\Message\GetMoviesQuery;
use App\Domain\Message\MovieAddedEvent;
use App\Domain\Message\MovieMessageInterface;

final class MovieLibrary implements MovieLibraryInterface
{
    public function __construct(private readonly MovieOwnerRepositoryInterface $ownerRepository, private readonly MovieFactoryInterface         $movieFactory)
    {
    }

    public function addMovie(AddMovieCommand $command): MovieMessageInterface
    {
        $this->getMovieOwner($command)
            ->addMovie(
                $this->movieFactory->create($command)
            );

        return MovieAddedEvent::fromCommand($command);
    }

    private function getMovieOwner(MovieOwnerIdAwareInterface $message): MovieOwnerInterface
    {
        return $this->ownerRepository->getMovieOwner(
            $message->getMovieOwnerId()
        );
    }

    public function getMovie(GetMovieByNameQuery $query): MovieInterface
    {
        return $this->getMovieOwner($query)
            ->getMovieByName($query->getName());
    }

    public function getMovies(GetMoviesQuery $query): iterable
    {
        return $this->getMovieOwner($query)
            ->getMovies();
    }
}
