<?php

namespace App\Repository;

use App\Entity\Festival;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Festival|null find($id, $lockMode = null, $lockVersion = null)
 * @method Festival|null findOneBy(array $criteria, array $orderBy = null)
 * @method Festival[]    findAll()
 * @method Festival[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FestivalRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Festival::class);
    }

    // /**
    //  * @return Festival[] Returns an array of Festival objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Festival
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
