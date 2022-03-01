<?php

namespace App\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\UserPaciente;
use App\Entity\Paciente;
use App\Entity\Sistema;
use App\Entity\Internacion;
use App\Entity\InternacionCama;
use App\Entity\Cama;
use App\Entity\Sala;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use JMS\Serializer\SerializationContext;


/**
 * Class Sistemas Controller
 *
 * @Route("/api/sistemas")
 */
class SistemasController extends FOSRestController
{

  /**
   * @Route("/index", name="sistemas_index", methods={"GET"})
   * @SWG\Response(response=200, description="Listado de Sistemas")
   * @SWG\Tag(name="Sistemas")
   *      
   * @param ParamFetcher $pf
   */
  public function index(Request $request): Response
  {        
    $serializer = $this->get('jms_serializer');
    $sistemas = $this->getDoctrine()->getRepository(Sistema::class)->findAll();
    return new Response($serializer->serialize($sistemas, "json", SerializationContext::create()->enableMaxDepthChecks()));
  }

  /**
   * @Route("/medicos", name="medicos", methods={"GET"})
   * @SWG\Response(response=200, description="Médicos de un sistema")
   * @SWG\Tag(name="Sistemas")
   * @QueryParam(name="sistema", strict=false, nullable=true, allowBlank=false, description="Sistema Id")
   * @QueryParam(name="paciente", strict=true, nullable=false, allowBlank=false, description="Paciente Id")
   *      
   * @param ParamFetcher $pf
   */
  public function getMedicosDeUnSistema(Request $request, ParamFetcher $pf): Response
  {        

    $sistemaId = $pf->get('sistema') ? $pf->get('sistema') : $this->getUser()->getSistema()->getId();
    $pacienteId = $pf->get('paciente');

    $users = $this->getDoctrine()->getRepository(User::class)->findUsersBySistema($sistemaId);

    foreach ($users as $key => $value) {

      //si es jefe lo quito
      if (in_array("ROLE_JEFE", $value["roles"])) {

        unset($users[$key]);

      } else {

        $yaExisteLaRelacion = $this->getDoctrine()->getRepository(UserPaciente::class)->findBy(['user' => $value["id"], 
                                                                                                'paciente' => $pacienteId,
                                                                                                'fecha_hasta' => null]);
  
        $users[$key]["asignado"] = $yaExisteLaRelacion ? true : false;                                                            

      }

    }

    $serializer = $this->get('jms_serializer');

    return new Response($serializer->serialize($users, "json", SerializationContext::create()->enableMaxDepthChecks()));
  
  }

  /**
   * @Route("/sistemasDestino", name="sistemas_destino", methods={"GET"})
   * @SWG\Response(response=200, description="Sistemas destino de un sistema")
   * @SWG\Tag(name="Sistemas")
   *      
   * @param ParamFetcher $pf
   */
  public function getSistemasDestino(Request $request): Response
  {        
    $serializer = $this->get('jms_serializer');
    $sistemasDestino = $this->getUser()->getSistema()->getSistemasDestino();
    return new Response($serializer->serialize($sistemasDestino, "json", SerializationContext::create()->enableMaxDepthChecks()));
  }

  /**
   * @Route("/salas", name="salas", methods={"GET"})
   * @SWG\Response(response=200, description="Salas de un sistema")
   * @SWG\Tag(name="Sistemas")
   * @QueryParam(name="id", strict=true, nullable=false, allowBlank=false, description="Sistema Id")
   *      
   * @param ParamFetcher $pf
   */
  public function getSalasDeUnSistema(Request $request, ParamFetcher $pf): Response
  {        
    $serializer = $this->get('jms_serializer');
    $salas = $this->getDoctrine()->getRepository(Sala::class)->findSalasBySistema($pf->get('id'));

    foreach ($salas as $key => $value) {     
      $camasTotal = $this->getDoctrine()->getRepository(Cama::class)->countCamasTotalBySala($value["id"]);
      $camasDisponibles = $this->getDoctrine()->getRepository(Cama::class)->countCamasDisponiblesBySala($value["id"]);
      $salas[$key]["camas_total"] = $camasTotal;
      $salas[$key]["camas_disponibles"] = $camasDisponibles;
      $salas[$key]["camas_ocupadas"] = $camasTotal - $camasDisponibles;
    }

    return new Response($serializer->serialize($salas, "json"));
  }

}
