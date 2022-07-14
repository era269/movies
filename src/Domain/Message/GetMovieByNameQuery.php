<?php

declare(strict_types=1);

namespace App\Domain\Message;

use App\Domain\MovieOwnerId;
use App\Traits\MovieOwnerIdAwareTrait;

final class GetMovieByNameQuery implements MovieMessageInterface
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
