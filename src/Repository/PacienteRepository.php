<?php

namespace App\Repository;

use App\Entity\Paciente;
use App\Entity\Internacion;
use App\Entity\InternacionCama;
use App\Entity\Cama;
use App\Entity\Sala;
use App\Entity\Sistema;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Paciente|null find($id, $lockMode = null, $lockVersion = null)
 * @method Paciente|null findOneBy(array $criteria, array $orderBy = null)
 * @method Paciente[]    findAll()
 * @method Paciente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PacienteRepository extends ServiceEntityRepository
{
	
	public function __construct(ManagerRegistry $registry)
	{
			parent::__construct($registry, Paciente::class);
	}

	public function findAllPacientesInternados($sistemaId, $salaId)
	{    
		$query = $this->createQueryBuilder('p')
					->select('p.id, p.dni, p.apellido, p.nombre, sist.descrip as sistema, s.nombre as sala, c.numero as cama, i.id AS internacionId, i.fecha_egreso, i.fecha_obito')
					->innerJoin('App:Internacion', 'i', 'WITH', 'i.paciente = p.id')
					->innerJoin('App:InternacionCama', 'ic', 'WITH', 'i.id = ic.internacion')
					->innerJoin('App:Cama', 'c', 'WITH', 'c.id = ic.cama')
					->innerJoin('App:Sala', 's', 'WITH', 's.id = c.sala')
					->innerJoin('App:Sistema', 'sist', 'WITH', 'sist.id = s.sistema')
					->where('ic.fecha_hasta IS NULL')
					->andWhere('sist.id = :sistemaId')
					->setParameter('sistemaId', $sistemaId)
					->orderBy('p.apellido', 'ASC');

		if($salaId){
			$query->andWhere('s.id = :salaId')->setParameter('salaId', $salaId);
		}
					
		return $query->getQuery()->getResult();
		
	}

	public function findAllPacientesEgresados()
	{    
		return $this->createQueryBuilder('p')
			->select('p.id, p.dni, p.apellido, p.nombre, i.fecha_egreso, i.fecha_obito')
			->innerJoin('App:Internacion', 'i', 'WITH', 'i.paciente = p.id')
			->where('i.fecha_egreso IS NOT NULL')
			->orderBy('p.apellido', 'ASC')
			->getQuery()
			->getResult();
	}

	public function findAllPacientesFallecidos()
	{    
		return $this->createQueryBuilder('p')
			->select('p.id, p.dni, p.apellido, p.nombre, i.fecha_egreso, i.fecha_obito')
			->innerJoin('App:Internacion', 'i', 'WITH', 'i.paciente = p.id')
			->where('i.fecha_obito IS NOT NULL')
			->orderBy('p.apellido', 'ASC')
			->getQuery()
			->getResult();
	}

	public function findPaciente($pacienteId)
	{    
		return $this->createQueryBuilder('p')
			->select('p.id, p.dni, p.apellido, p.nombre, p.direccion, p.telefono, p.email, p.fecha_nacimiento, p.obra_social, p.antecedentes, 
								p.contacto_nombre, p.contacto_apellido, p.contacto_telefono, p.contacto_parentesco')
			->where('p.id = :pacienteId')
			->setParameter('pacienteId', $pacienteId)
			->getQuery()
			->getOneOrNullResult();
	}
    
}
