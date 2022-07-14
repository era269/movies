<?php

declare(strict_types=1);

namespace App\Domain;

interface MovieOwnerIdAwareInterface
{
    public function getMovieOwnerId(): MovieOwnerId;
}
