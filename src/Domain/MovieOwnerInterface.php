<?php

declare(strict_types=1);

namespace App\Domain;

interface MovieOwnerInterface
{
    public function addMovie(MovieInterface $movie): void;
    public function hasMovie(string $movieName): bool;

    /**
     * @return MovieInterface[]
     */
    public function getMovies(): iterable;

    public function getMovieByName(string $name): MovieInterface;
}
