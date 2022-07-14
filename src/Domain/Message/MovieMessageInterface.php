<?php

declare(strict_types=1);

namespace App\Domain\Message;

use App\Domain\MessageInterface;
use App\Domain\MovieOwnerIdAwareInterface;

interface MovieMessageInterface extends MessageInterface, MovieOwnerIdAwareInterface
{
}
