<?php

declare(strict_types=1);

namespace App\Traits;

use App\Domain\Message\AddMovieCommand;
use App\Domain\Message\GetMovieByNameQuery;
use App\Domain\Message\GetMoviesQuery;
use App\Domain\Message\MovieMessageInterface;
use App\Domain\MovieInterface;
use App\Domain\MovieOwnersInterface;

trait MovieOwnersDecoratorTrait
{
    private MovieOwnersInterface $decorated;

    public function addMovie(AddMovieCommand $command): MovieMessageInterface
    {
        return $this->decorated->addMovie($command);
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
