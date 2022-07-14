<?php

declare(strict_types=1);

namespace App\Domain\Message;

use App\Domain\MessageInterface;
use App\Domain\MovieOwnerId;
use App\Domain\MovieOwnerIdAwareInterface;
use App\Traits\MovieOwnerIdAwareTrait;

final class GetMoviesQuery implements MessageInterface, MovieOwnerIdAwareInterface
{
    use MovieOwnerIdAwareTrait;

    public function __construct(MovieOwnerId $id)
    {
        $this->setMovieOwnerId($id);
    }
}
