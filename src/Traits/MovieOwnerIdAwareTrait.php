<?php

declare(strict_types=1);

namespace App\Traits;

use App\Domain\MovieOwnerId;

trait MovieOwnerIdAwareTrait
{
    private MovieOwnerId $movieOwnerId;

    public function getMovieOwnerId(): MovieOwnerId
    {
        return $this->movieOwnerId;
    }

    private function setMovieOwnerId(MovieOwnerId $movieOwnerId): void
    {
        $this->movieOwnerId = $movieOwnerId;
    }
}
