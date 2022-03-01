<?php

namespace App\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\Aviso;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Mime\Message;

/**
 * Class Paciente Controller
 *
 * @Route("/api/alertas")
 */
class AvisosController extends FOSRestController
{

    /**
     * @Route("/index", name="alertas_index", methods={"GET"})
     * @SWG\Response(response=200, description="Avisos de un usuario")
     * @SWG\Tag(name="Alertas")
     * @QueryParam(name="usuarioId", strict=true, nullable=false, allowBlank=false, description="Usuario Id")
     *      
     */
    public function getAvisos(Request $request, ParamFetcher $pf): Response
    {        
      $avisos = $this->getDoctrine()->getRepository(Aviso::class)->findAllAvisos($pf->get('usuarioId'));

      $serializer = $this->get('jms_serializer'); 
    
      return new Response($serializer->serialize($avisos, "json"), 200);
    }

    /**
     * @Route("/cantAvisosSinLeer", name="alertas_cant", methods={"GET"})
     * @SWG\Response(response=200, description="Cantidad de avisos sin leer de un usuario")
     * @SWG\Tag(name="Alertas")
     * @QueryParam(name="usuarioId", strict=true, nullable=false, allowBlank=false, description="Usuario Id")
     *      
     */
    public function getCantAvisosSinLeer(Request $request, ParamFetcher $pf): Response
    {        
      $avisos = $this->getDoctrine()->getRepository(Aviso::class)->getCantAvisosSinLeer($pf->get('usuarioId'));

      $serializer = $this->get('jms_serializer'); 
    
      return new Response($serializer->serialize($avisos, "json"), 200);
    }

    /**
     * @Route("/leida", name="alertas_leida", methods={"GET"})
     * @SWG\Response(response=200, description="Marcar aviso como leído")
     * @SWG\Tag(name="Alertas")
     * @QueryParam(name="id", strict=true, nullable=false, allowBlank=false, description="Aviso Id")
     *      
     */
    public function marcarComoLeida(Request $request, ParamFetcher $pf): Response
    {        
      $aviso = $this->getDoctrine()->getRepository(Aviso::class)->find($pf->get('id'));
      
      $aviso->setLeido(true);
      
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($aviso);
      $entityManager->flush();

      $serializer = $this->get('jms_serializer'); 
    
      return new Response("Alerta marcada como leída", 200);
    }

}
