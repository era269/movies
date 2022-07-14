<?php

namespace App\Repository;

use App\Entity\MovieRating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MovieRating>
 *
 * @method MovieRating|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovieRating|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovieRating[]    findAll()
 * @method MovieRating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovieRating::class);
    }
}
