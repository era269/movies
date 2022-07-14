<?php

namespace App\Repository;

use App\Entity\RatingType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RatingType>
 *
 * @method RatingType|null find($id, $lockMode = null, $lockVersion = null)
 * @method RatingType|null findOneBy(array $criteria, array $orderBy = null)
 * @method RatingType[]    findAll()
 * @method RatingType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RatingTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RatingType::class);
    }
}
