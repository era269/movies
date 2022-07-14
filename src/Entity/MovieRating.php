<?php

namespace App\Entity;

use App\Repository\MovieRatingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovieRatingRepository::class)
 */
class MovieRating
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=RatingType::class, cascade={"persist"})
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Movie::class, inversedBy="movieRatings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $movie;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    private float $value;

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): RatingType
    {
        return $this->type;
    }

    public function setType(RatingType $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getMovie(): Movie
    {
        return $this->movie;
    }

    public function setMovie(Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }
}
