<?php

declare(strict_types=1);

namespace App\Domain;

interface MovieOwnerRepositoryInterface
{
    public function getMovieOwner(MovieOwnerId $id): MovieOwnerInterface;
}
