<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Message\AddUserMovieCommand;

interface MovieFactoryInterface
{
    public function create(AddUserMovieCommand $command): MovieInterface;
}
