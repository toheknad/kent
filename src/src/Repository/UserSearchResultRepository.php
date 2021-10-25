<?php

namespace App\Repository;

use App\Entity\UserSearchResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserSearchResult|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserSearchResult|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserSearchResult[]    findAll()
 * @method UserSearchResult[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserSearchResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserSearchResult::class);
    }

    // /**
    //  * @return UserSearchResult[] Returns an array of UserSearchResult objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserSearchResult
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
