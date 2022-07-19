<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Message\AddMovieCommand;
use App\Domain\Message\GetMovieByNameQuery;
use App\Domain\Message\GetMoviesQuery;
use App\Domain\Message\MovieMessageInterface;

interface MovieOwnersInterface
{
    public function addMovie(AddMovieCommand $command): MovieMessageInterface;

    public function getMovie(GetMovieByNameQuery $query): MovieInterface;

    /**
     * @return MovieInterface[]
     */
    public function getMovies(GetMoviesQuery $query): iterable;
}
