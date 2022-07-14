<?php

declare(strict_types=1);

namespace App\Domain\Message;

use App\Domain\MessageInterface;
use App\Domain\MovieOwnerId;

final class GetMovieByNameQuery implements MessageInterface
{
    private string $name;
    private MovieOwnerId $id;

    public function __construct(MovieOwnerId $id, string $name)
    {
        $this->name = $name;
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): MovieOwnerId
    {
        return $this->id;
    }
}
