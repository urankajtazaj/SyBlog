<?php

namespace App\Repository;

use App\Entity\Studnenti;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Studnenti|null find($id, $lockMode = null, $lockVersion = null)
 * @method Studnenti|null findOneBy(array $criteria, array $orderBy = null)
 * @method Studnenti[]    findAll()
 * @method Studnenti[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudnentiRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Studnenti::class);
    }

    // /**
    //  * @return Studnenti[] Returns an array of Studnenti objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Studnenti
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
