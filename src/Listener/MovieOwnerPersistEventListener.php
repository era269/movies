<?php

declare(strict_types=1);

namespace App\Listener;

use App\Domain\Message\MovieAddedEvent;
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

    public function __invoke(MovieAddedEvent $event): void
    {
        $this->om->flush();
    }
}
