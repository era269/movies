<?php

declare(strict_types=1);

namespace App\Domain\Message;

use App\Domain\MovieOwnerId;
use App\Traits\MovieOwnerIdAwareTrait;

final class GetMovieByNameQuery implements MovieMessageInterface
{
    use MovieOwnerIdAwareTrait;

    public function __construct(MovieOwnerId $id, private readonly string $name)
    {
        $this->setMovieOwnerId($id);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
