<?php

namespace App\Repository;

use App\Entity\VolunteerAvailability;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method VolunteerAvailability|null find($id, $lockMode = null, $lockVersion = null)
 * @method VolunteerAvailability|null findOneBy(array $criteria, array $orderBy = null)
 * @method VolunteerAvailability[]    findAll()
 * @method VolunteerAvailability[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvailabilityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VolunteerAvailability::class);
    }

    // /**
    //  * @return VolunteersAvailability[] Returns an array of VolunteersAvailability objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VolunteersAvailability
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
