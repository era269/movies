<?php

declare(strict_types=1);

namespace App\Factory;

use App\Domain\Message\AddMovieCommand;
use App\Domain\MovieFactoryInterface;
use App\Entity\Actor;
use App\Entity\Director;
use App\Entity\Movie;
use App\Entity\MovieRating;
use App\Entity\RatingType;
use App\Repository\ActorRepository;
use App\Repository\DirectorRepository;
use App\Repository\RatingTypeRepository;

final class MovieFactory implements MovieFactoryInterface
{
    public function __construct(private readonly ActorRepository      $actorRepository, private readonly RatingTypeRepository $ratingTypeRepository, private readonly DirectorRepository   $directorRepository)
    {
    }

    public function create(AddMovieCommand $command): Movie
    {
        $movie = new Movie();
        $movie
            ->setName($command->getName())
            ->setReleaseDate($command->getReleaseDate())
            ->setDirector(
                $this->getDirector($command)
            );
        foreach ($command->getCasts() as $cast) {
            $movie->addCast(
                $this->getCast($cast)
            );
        }

        foreach ($command->getRatings() as $ratingType => $ratingValue) {
            $movie->addRating(
                $this->getMovieRating($ratingType, $ratingValue)
            );
        }

        return $movie;
    }

    private function getDirector(AddMovieCommand $command): Director
    {
        return $this->directorRepository->findOneByName($command->getDirector())
            ?? new Director($command->getDirector());
    }

    private function getCast(string $cast): Actor
    {
        return $this->actorRepository->findOneByName($cast)
            ?? new Actor($cast);
    }

    private function getMovieRating(string $ratingType, float $ratingValue): MovieRating
    {
        return (new MovieRating())
            ->setType(
                $this->ratingTypeRepository->findOneByName($ratingType)
                ?? new RatingType($ratingType)
            )
            ->setValue($ratingValue);
    }
}
