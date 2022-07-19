<?php

declare(strict_types=1);

namespace App\Domain\Message\Factory;

use App\Domain\Message\AddMovieCommand;
use App\Domain\Message\FailedToAddMovieEvent;
use App\Domain\Message\MovieAddedEvent;

final class MovieOwnerEventFactory
{
    public function createMovieAddedEvent(AddMovieCommand $addUserMovieCommand): MovieAddedEvent
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
    public function createFailedToAddMovieEvent(AddMovieCommand $addUserMovieCommand): FailedToAddMovieEvent
    {
        return new FailedToAddMovieEvent(
            $addUserMovieCommand->getMovieOwnerId(),
            $addUserMovieCommand->getName(),
            'Movie already exists'
        );
    }
}
