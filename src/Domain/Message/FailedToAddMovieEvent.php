<?php

declare(strict_types=1);

namespace App\Domain\Message;

use App\Domain\MovieOwnerId;
use App\Traits\MovieOwnerIdAwareTrait;

final class FailedToAddMovieEvent implements MovieMessageInterface
{
    use MovieOwnerIdAwareTrait;

    private string $message = 'Movie already exists';

    public function __construct(MovieOwnerId $movieOwnerId, private readonly string $movieName)
    {
        $this->setMovieOwnerId($movieOwnerId);
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getMovieName(): string
    {
        return $this->movieName;
    }

    public static function fromCommand(AddMovieCommand $command): self
    {
        return new self(
            $command->getMovieOwnerId(),
            $command->getName()
        );
    }
}
