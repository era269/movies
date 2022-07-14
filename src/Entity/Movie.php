<?php

namespace App\Entity;

use App\Domain\MovieInterface;
use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie implements MovieInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Actor::class, cascade={"persist"})
     */
    private $casts;

    /**
     * @ORM\Column(type="date")
     */
    private $releaseDate;

    /**
     * @ORM\ManyToOne(targetEntity=Director::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $director;

    /**
     * @ORM\OneToMany(mappedBy="movie", targetEntity=MovieRating::class, orphanRemoval=true, cascade={"persist"})
     */
    private $ratings;

    /**
     * @ORM\ManyToOne(targetEntity=MovieOwner::class, inversedBy="movies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    public function __construct()
    {
        $this->casts = new ArrayCollection();
        $this->ratings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     *
     * @return Collection<int, Actor>
     */
    public function getCasts(): Collection
    {
        return $this->casts;
    }

    public function addCast(Actor $cast): self
    {
        if (!$this->casts->contains($cast)) {
            $this->casts[] = $cast;
        }

        return $this;
    }

    public function removeCast(Actor $cast): self
    {
        $this->casts->removeElement($cast);

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getDirector(): ?Director
    {
        return $this->director;
    }

    public function setDirector(?Director $director): self
    {
        $this->director = $director;

        return $this;
    }

    /**
     * @return Collection<int, MovieRating>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(MovieRating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings[] = $rating;
            $rating->setMovie($this);
        }

        return $this;
    }

    public function removeRating(MovieRating $rating): self
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getMovie() === $this) {
                $rating->setMovie(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?MovieOwner
    {
        return $this->owner;
    }

    public function setOwner(MovieOwner $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
