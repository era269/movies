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

    public function add(MovieOwner $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MovieOwner $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return MovieOwners[] Returns an array of MovieOwners objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MovieOwners
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function getMovieOwner(MovieOwnerId $id): MovieOwnerInterface
    {
        return $this->find($id->getValue())
            ?? $this->throwOutOfBountsException();
    }

    private function throwOutOfBountsException(): MovieOwnerInterface
    {
        throw new OutOfBoundsException('Movie owner not found');
    }
}
