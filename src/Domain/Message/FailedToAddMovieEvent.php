<?php

declare(strict_types=1);

namespace App\Domain\Message;

use App\Domain\MovieOwnerId;
use App\Traits\MovieOwnerIdAwareTrait;

final class FailedToAddMovieEvent implements MovieMessageInterface
{
    use MovieOwnerIdAwareTrait;

    private string $message;
    private string $movieName;

    public function __construct(MovieOwnerId $movieOwnerId, string $movieName, string $message)
    {
        $this->message = $message;
        $this->movieName = $movieName;
        $this->setMovieOwnerId($movieOwnerId);
    }
}
