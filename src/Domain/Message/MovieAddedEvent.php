<?php

declare(strict_types=1);

namespace App\Domain\Message;

use App\Domain\MessageInterface;
use App\Domain\MovieOwnerId;
use App\Domain\MovieOwnerIdAwareInterface;
use App\Traits\MovieOwnerIdAwareTrait;
use DateTimeImmutable;
use DateTimeInterface;

final class MovieAddedEvent implements MovieMessageInterface
{
    use MovieOwnerIdAwareTrait;

    private readonly DateTimeInterface $occurredAt;
    /**
     * @return DateTimeImmutable|DateTimeInterface
     */
    public function getOccurredAt(): \DateTimeImmutable|\DateTimeInterface
    {
        return $this->occurredAt;
    }

    /**
     * @param string[] $casts
     * @param array<string, float> $ratings
     */
    public function __construct(
        MovieOwnerId $modelId,
        private readonly string   $name,
        private readonly array    $casts,
        private readonly DateTimeInterface $releaseDate,
        private readonly string   $director,
        private readonly array    $ratings
    )
    {
        $this->occurredAt = new DateTimeImmutable();
        $this->setMovieOwnerId($modelId);
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getCasts(): array
    {
        return $this->casts;
    }

    public function getReleaseDate(): DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function getDirector(): string
    {
        return $this->director;
    }

    /**
     * @return array<string, float>
     */
    public function getRatings(): array
    {
        return $this->ratings;
    }

    public static function fromCommand(AddMovieCommand $command): self
    {
        return new self(
            $command->getMovieOwnerId(),
            $command->getName(),
            $command->getCasts(),
            $command->getReleaseDate(),
            $command->getDirector(),
            $command->getRatings()
        );
    }
}
