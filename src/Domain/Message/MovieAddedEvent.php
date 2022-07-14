<?php

declare(strict_types=1);

namespace App\Domain\Message;

use App\Domain\MessageInterface;
use App\Domain\MovieOwnerId;
use DateTimeImmutable;
use DateTimeInterface;

final class MovieAddedEvent implements MessageInterface
{
    private string $name;
    /**
     * @var string[]
     */
    private array $casts;
    private DateTimeInterface $releaseDate;
    private string $director;
    /**
     * @var array<string, float>
     */
    private array $ratings;

    private DateTimeInterface $occurredAt;

    private MovieOwnerId $modelId;

    /**
     * @return DateTimeImmutable|DateTimeInterface
     */
    public function getOccurredAt()
    {
        return $this->occurredAt;
    }

    public function getModelId(): MovieOwnerId
    {
        return $this->modelId;
    }

    /**
     * @param string[] $casts
     * @param array<string, float> $ratings
     */
    public function __construct(
        MovieOwnerId $modelId,
        string   $name,
        array    $casts,
        DateTimeInterface $releaseDate,
        string   $director,
        array    $ratings
    )
    {
        $this->name = $name;
        $this->casts = $casts;
        $this->releaseDate = $releaseDate;
        $this->director = $director;
        $this->ratings = $ratings;
        $this->occurredAt = new DateTimeImmutable();
        $this->modelId = $modelId;
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
}
