<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Message\UserMovieAddedEvent;

interface MovieOwnerInterface
{
    public function addMovie(MovieInterface $movie): void;
    public function getMovieByName(string $name): MovieInterface;
}
