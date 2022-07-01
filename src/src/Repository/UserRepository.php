<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserSearchResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    // /**
    //  * @return User[] Returns an array of User objects
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
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function getUserByFilter(User $user)
    {
        $userFilter = $user->getUserFilter();

        $subQuery = $this->getEntityManager()->createQueryBuilder()
            ->select('IDENTITY(usr.userTo) as id')
            ->from(UserSearchResult::class, 'usr')
            ->andWhere('usr.userFrom = :currentUser')
            ->setParameter('currentUser', $user->getId());
        $showedUsers = $subQuery->getQuery()->getArrayResult();
        $test = implode(', ', array_map(function ($value) {
            return $value['id'];
        }, $showedUsers));
        print_r('<pre>');
        print_r($test);
        print_r('</pre>');
        print_r('fdsfsd');

        $query = $this->createQueryBuilder('u')
            ->andWhere('u.age > :ageFrom')
            ->andWhere('u.age < :ageTo')
            ->andWhere('u.city = :city')
            ->andWhere('u.id != :currentUserId')
            ->setParameter('ageFrom', $userFilter->getAgeFrom())
            ->setParameter('ageTo', $userFilter->getAgeTo())
            ->setParameter('city', $user->getCity())
            ->setParameter('currentUserId', $user->getId());
        if ($showedUsers) {
            $query->andWhere('u.id NOT IN (:showedUsersId)')
                ->setParameter('showedUsersId', $showedUsers);
        }

//        if ($userFilter->getGender() !== 'неважно') {
//            $query->andWhere('u.gender = :gender')
//                ->setParameter('gender', (string)$userFilter->getGender());
//        }
//


        return $query->getQuery()
            ->getResult();
    }


}
