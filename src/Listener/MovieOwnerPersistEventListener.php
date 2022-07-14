<?php

declare(strict_types=1);

namespace App\Listener;

use App\Domain\Message\UserMovieAddedEvent;
use Doctrine\Persistence\ObjectManager;

final class MovieOwnerPersistEventListener
{
    private ObjectManager $om;

    public function __construct(
        ObjectManager $om
    )
    {
        $this->om = $om;
    }

    public function __invoke(UserMovieAddedEvent $event): void
    {
        $this->om->flush();
    }
}
