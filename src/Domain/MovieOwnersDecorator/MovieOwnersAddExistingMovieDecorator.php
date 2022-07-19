<?php

declare(strict_types=1);

namespace App\Domain\MovieOwnersDecorator;

use App\Domain\Message\AddMovieCommand;
use App\Domain\Message\FailedToAddMovieEvent;
use App\Domain\Message\MovieMessageInterface;
use App\Domain\MovieOwnerRepositoryInterface;
use App\Domain\MovieOwnersInterface;
use App\Traits\MovieOwnersDecoratorTrait;

final class MovieOwnersAddExistingMovieDecorator implements MovieOwnersInterface
{
    use MovieOwnersDecoratorTrait;

    private MovieOwnerRepositoryInterface $repository;

    public function __construct(
        MovieOwnersInterface          $decorated,
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
}
