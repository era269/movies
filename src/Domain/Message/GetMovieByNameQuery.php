<?php

declare(strict_types=1);

namespace App\Domain\Message;

use App\Domain\MessageInterface;
use App\Domain\MovieOwnerId;
use App\Domain\MovieOwnerIdAwareInterface;
use App\Traits\MovieOwnerIdAwareTrait;

final class GetMovieByNameQuery implements MessageInterface, MovieOwnerIdAwareInterface
{
    use MovieOwnerIdAwareTrait;

    private string $name;

    public function __construct(MovieOwnerId $id, string $name)
    {
        $this->name = $name;
        $this->setMovieOwnerId($id);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
