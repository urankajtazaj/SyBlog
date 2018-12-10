<?php

namespace App\Repository;

use App\Entity\Sharer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Sharer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sharer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sharer[]    findAll()
 * @method Sharer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SharerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Sharer::class);
    }

    // /**
    //  * @return Sharer[] Returns an array of Sharer objects
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
    public function findOneBySomeField($value): ?Sharer
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
