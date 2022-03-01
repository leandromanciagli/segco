<?php

namespace App\Extensions;

use App\Entity\Internacion;
use App\Entity\InternacionCama;
use App\Entity\UserPaciente;
use Symfony\Component\HttpFoundation\Response;

trait InternacionTrait {

  private function cambiarEstado($internacionId, $estado, $motivoEgreso = null) {

    $serializer = $this->get('jms_serializer');    

    $internacion = $this->getDoctrine()->getRepository(Internacion::class)->find($internacionId);

    if (!$internacion) {

      $error = [ 
        "message" => "La internación id ".$internacionId." no existe",
        "title" => "No se encontró la internación",
      ];

      return new Response($serializer->serialize($error, "json"), 404);

    }

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->getConnection()->beginTransaction();

    try {

      $internacionCamaVigente = $this->getDoctrine()->getRepository(InternacionCama::class)->findVigente($internacionId);
      $internacionCamaVigente->setFechaHasta(new \DateTime);
  
      if ($estado == 'obito') {
        $internacion->setFechaObito(new \DateTime);
      } else {
        $internacion->setFechaEgreso(new \DateTime);
        $internacion->setMotivoEgreso($motivoEgreso);
      }
  
      $cama = $internacionCamaVigente->getCama();
      $cama->setEstado("libre");
  
      $sistema = $cama->getSala()->getSistema();
      $sistema->setCamasDisponibles($sistema->getCamasDisponibles() + 1);
      $sistema->setCamasOcupadas($sistema->getCamasOcupadas() - 1);

      //desasignar todos los médicos para ese paciente en el sistema origen.
      $usersPacientes = $this->getDoctrine()->getRepository(UserPaciente::class)
                             ->findBy(["paciente" => $internacion->getPaciente(), "fecha_hasta" => null]);

      if ($usersPacientes) {

        foreach ($usersPacientes as $userPaciente) {
          $userPaciente->setFechaHasta(new \DateTime);
          $entityManager->persist($userPaciente);
        }

      } 
  
      $entityManager->flush();
      $entityManager->getConnection()->commit(); 

    } catch (\Throwable $th) {

      $entityManager->getConnection()->rollBack();

      $error = [ 
        "message" => "Se produjo un error al intentar el cambio de estado".$th->getMessage(),
      ];

      return new Response($serializer->serialize($error, "json"), 500);

    }
        
    return new Response("Se efectuó el cambio de estado", 200);

  }

}