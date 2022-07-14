<?php

namespace App\Entity;

use App\Domain\MovieInterface;
use App\Domain\MovieOwnerId;
use App\Domain\MovieOwnerInterface;
use App\Factory\MovieFactory;
use App\Repository\MovieOwnerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OutOfBoundsException;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * @ORM\Entity(repositoryClass=MovieOwnerRepository::class)
 */
class MovieOwner implements MovieOwnerInterface
{
    public EventDispatcherInterface $eventDispatcher;
    public MovieFactory $movieFactory;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="movieOwner")
     * @ORM\JoinColumn(nullable=false)
     */
    private $identity;
    /**
     * @ORM\OneToMany(targetEntity=Movie::class, mappedBy="owner", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $movies;

    public function __construct()
    {
        $this->movies = new ArrayCollection();
    }

    public function getId(): MovieOwnerId
    {
        return new MovieOwnerId($this->id);
    }

    public function getIdentity(): ?User
    {
        return $this->identity;
    }

    public function setIdentity(User $identity): self
    {
        $this->identity = $identity;

        return $this;
    }

    public function addMovie(MovieInterface $movie): void
    {
        if (!$this->movies->contains($movie)) {
            $this->movies[] = $movie;
            $movie->setOwner($this);
        }
    }

    public function removeMovie(Movie $movie): self
    {
        if ($this->movies->removeElement($movie)) {
            // set the owning side to null (unless already changed)
            if ($movie->getOwner() === $this) {
                $movie->setOwner(null);
            }
        }

        return $this;
    }

    public function getMovieByName(string $name): MovieInterface
    {
        return $this->getMovies()
            ->filter(
                fn (Movie $m) => $m->getName() === $name
            )
            ->first()
            ?: $this->throwMovieNotFound();
    }

    /**
     * @return Collection<int, MovieInterface>
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    private function throwMovieNotFound(): Movie
    {
        throw new OutOfBoundsException('Movie not found');
    }
}
