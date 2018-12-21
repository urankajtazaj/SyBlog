<?php

namespace App\Repository;

use App\Entity\CommentVotes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CommentVotes|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentVotes|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentVotes[]    findAll()
 * @method CommentVotes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentVotesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CommentVotes::class);
    }

    // /**
    //  * @return CommentVotes[] Returns an array of CommentVotes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function getVoteCount($post, $comment) {
        return $this->createQueryBuilder('v')
            ->andWhere('v.comment = :comment')
            ->setParameter('comment', $comment)
            ->andWhere('v.post = :post')
            ->setParameter('post', $post)
            ->select('sum(v.type) as count')
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?CommentVotes
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
