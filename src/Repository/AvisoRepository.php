<?php

namespace App\Repository;

use App\Entity\Aviso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Aviso|null find($id, $lockMode = null, $lockVersion = null)
 * @method Aviso|null findOneBy(array $criteria, array $orderBy = null)
 * @method Aviso[]    findAll()
 * @method Aviso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvisoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Aviso::class);
    }

    public function findAllAvisos($usuarioId)
    {
        return $this->createQueryBuilder('a')
            ->select('a.id, a.evento, a.mensaje, a.detalle, a.leido')
            ->innerJoin('App:User', 'u', 'WITH', 'u.id = a.usuario')
            ->andWhere('u.id = :usuarioId')
            ->setParameter('usuarioId', $usuarioId)
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getCantAvisosSinLeer($usuarioId)
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->innerJoin('App:User', 'u', 'WITH', 'u.id = a.usuario')
            ->andWhere('u.id = :usuarioId')
            ->andWhere('a.leido = :leido')
            ->setParameter('usuarioId', $usuarioId)
            ->setParameter('leido', false)
            ->getQuery()
            ->getSingleScalarResult();
    }

}
