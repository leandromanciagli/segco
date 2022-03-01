<?php

namespace App\Extensions;

use App\Entity\Aviso;
use Symfony\Component\Config\Definition\Exception\Exception;

class SistemaAvisos
{

    private $em;

    public function __construct($entityManager)
    {
        $this->em = $entityManager;
    }

    public function alertar(array $usuarios, string $mensaje, string $evento, $detalle = null)
    {

        foreach ($usuarios as $u){
            $aviso = new Aviso();
            $aviso->setUsuario($u);
            $aviso->setMensaje($mensaje);
            $aviso->setEvento($evento);
            $aviso->setDetalle($detalle);
            $aviso->setLeido(false);
            $this->em->persist($aviso);
            $this->em->flush();
        }


    }

}