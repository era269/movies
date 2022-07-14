<?php
declare(strict_types=1);

namespace App\Domain\Message;

use App\Domain\MessageInterface;
use App\Domain\MovieOwnerId;
use App\Domain\MovieOwnerIdAwareInterface;
use App\Dto\MovieDto;
use App\Traits\MovieOwnerIdAwareTrait;
use DateTimeImmutable;
use DateTimeInterface;

final class AddMovieCommand implements MovieMessageInterface
{
    use MovieOwnerIdAwareTrait;
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

    /**
     * @param string[] $casts
     * @param array<string, float> $ratings
     */
    public function __construct(
        MovieOwnerId      $movieOwnerId,
        string            $name,
        array             $casts,
        DateTimeInterface $releaseDate,
        string            $director,
        array             $ratings
    )
    {
        $this->setMovieOwnerId($movieOwnerId);
        $this->name = $name;
        $this->casts = $casts;
        $this->releaseDate = $releaseDate;
        $this->director = $director;
        $this->ratings = $ratings;
    }

    public static function fromDto(MovieOwnerId $movieOwnerId, MovieDto $dto, string $dateFormat = DateTimeInterface::RFC3339): self
    {
        return new self(
            $movieOwnerId,
            $dto->name,
            $dto->casts,
            DateTimeImmutable::createFromFormat($dateFormat, $dto->releaseDate),
            $dto->director,
            $dto->ratings
        );
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
