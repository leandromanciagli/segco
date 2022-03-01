<?php

namespace App\Repository;

use App\Entity\Sistema;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Sistema|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sistema|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sistema[]    findAll()
 * @method Sistema[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SistemaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sistema::class);
    }
    
    public function findAll()
    {
        return $this->createQueryBuilder('s')
            ->select('s.id, s.nombre, s.descrip, s.camas_total, s.camas_disponibles, s.camas_ocupadas')
            ->orderBy('s.id')
            ->getQuery()
            ->getResult()
        ;
    }
    
}
