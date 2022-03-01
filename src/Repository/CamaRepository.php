<?php

namespace App\Repository;

use App\Entity\Cama;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Cama|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cama|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cama[]    findAll()
 * @method Cama[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CamaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cama::class);
    }
    
    public function findPrimerCamaLibre($sistemaId)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('App:Sala', 's', 'WITH', 's.id = c.sala')
            ->innerJoin('App:Sistema', 'sist', 'WITH', 'sist.id = s.sistema')
            ->where('c.estado = :estado')
            ->andWhere('sist.id = :sistemaId')
            ->setParameter('estado', 'libre')
            ->setParameter('sistemaId', $sistemaId)
            ->orderBy('c.numero', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getNroCamaMax($salaDomicilio)
    {
        return $this->createQueryBuilder('c')
            ->select('c.numero')
            ->where('c.sala = :salaDomicilio')
            ->setParameter('salaDomicilio', $salaDomicilio)
            ->orderBy('c.numero', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function countCamasTotalBySala($sala)
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id) ')
            ->where('c.sala = :sala')
            ->setParameter('sala', $sala)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countCamasDisponiblesBySala($sala)
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.sala = :sala')
            ->andWhere('c.estado = :estado')
            ->setParameter('sala', $sala)
            ->setParameter('estado', 'libre')
            ->getQuery()
            ->getSingleScalarResult();
    }
    
}
