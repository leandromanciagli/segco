<?php

namespace App\Repository;

use App\Entity\Sala;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Sala|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sala|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sala[]    findAll()
 * @method Sala[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SalaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sala::class);
    }
    
    public function findSalasBySistema($sistemaId)
    {
        return $this->createQueryBuilder('s')
            ->select('s.id, s.nombre')
            ->andWhere('s.sistema = :val')
            ->setParameter('val', $sistemaId)
            ->getQuery()
            ->getResult()
        ;
    }
    
}
