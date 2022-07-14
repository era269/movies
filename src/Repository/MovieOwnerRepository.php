<?php

namespace App\Repository;

use App\Domain\MovieOwnerId;
use App\Domain\MovieOwnerInterface;
use App\Domain\MovieOwnerRepositoryInterface;
use App\Entity\MovieOwner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use OutOfBoundsException;

/**
 * @extends ServiceEntityRepository<MovieOwner>
 *
 * @method MovieOwner|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovieOwner|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovieOwner[]    findAll()
 * @method MovieOwner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieOwnerRepository extends ServiceEntityRepository implements MovieOwnerRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovieOwner::class);
    }

    public function getMovieOwner(MovieOwnerId $id): MovieOwnerInterface
    {
        return $this->find($id->getValue())
            ?? $this->throwOutOfBoundsException();
    }

    private function throwOutOfBoundsException(): MovieOwnerInterface
    {
        throw new OutOfBoundsException('Movie owner not found');
    }
}
