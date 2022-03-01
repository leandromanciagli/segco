<?php

namespace App\Repository;

use App\Entity\InternacionCama;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method InternacionCama|null find($id, $lockMode = null, $lockVersion = null)
 * @method InternacionCama|null findOneBy(array $criteria, array $orderBy = null)
 * @method InternacionCama[]    findAll()
 * @method InternacionCama[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InternacionCamaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InternacionCama::class);
    }

    public function findVigente($internacionId)
    {
        return $this->createQueryBuilder('ic')
            ->where('ic.internacion = :internacionId')
            ->andWhere('ic.fecha_hasta is null')
            ->setParameter('internacionId', $internacionId)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }

    /*
    public function findOneBySomeField($value): ?InternacionCama
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
