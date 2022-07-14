<?php

declare(strict_types=1);

namespace App\Domain\Message;

use App\Domain\MessageInterface;
use App\Domain\MovieOwnerId;

final class GetMoviesQuery implements MessageInterface
{
    private MovieOwnerId $id;

    public function __construct(MovieOwnerId $id)
    {
        $this->id = $id;
    }

    public function getId(): MovieOwnerId
    {
        return $this->id;
    }
}
