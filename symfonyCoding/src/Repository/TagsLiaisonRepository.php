<?php

namespace App\Repository;

use App\Entity\TagsLiaison;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TagsLiaison>
 *
 * @method TagsLiaison|null find($id, $lockMode = null, $lockVersion = null)
 * @method TagsLiaison|null findOneBy(array $criteria, array $orderBy = null)
 * @method TagsLiaison[]    findAll()
 * @method TagsLiaison[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagsLiaisonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TagsLiaison::class);
    }

//    /**
//     * @return TagsLiaison[] Returns an array of TagsLiaison objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TagsLiaison
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
