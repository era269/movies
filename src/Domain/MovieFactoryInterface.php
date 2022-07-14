<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Message\AddMovieCommand;

interface MovieFactoryInterface
{
    public function create(AddMovieCommand $command): MovieInterface;
}
