<?php

declare(strict_types=1);

namespace App\Domain\MovieLibraryDecorator;

use App\Domain\Message\AddMovieCommand;
use App\Domain\Message\FailedToAddMovieEvent;
use App\Domain\Message\MovieMessageInterface;
use App\Domain\MovieLibraryInterface;
use App\Domain\MovieOwnerRepositoryInterface;
use App\Traits\MovieOwnersDecoratorTrait;

final class MovieLibraryAddExistingMovieDecorator implements MovieLibraryInterface
{
    use MovieOwnersDecoratorTrait;

    public function __construct(
        MovieLibraryInterface         $decorated,
        private readonly MovieOwnerRepositoryInterface $repository
    )
    {
        $this->decorated = $decorated;
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
