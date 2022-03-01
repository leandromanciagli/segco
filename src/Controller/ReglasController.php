<?php

namespace App\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\Regla;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Mime\Message;

/**
 * 
 * Class Reglas Controller
 *
 * @Route("/api/reglas")
 */
class ReglasController extends FOSRestController
{
    /**
     * @Route("/index", name="reglas_index", methods={"GET"})
     * @SWG\Response(response=200, description="Listado de reglas del sistema")
     * @SWG\Tag(name="Regla")
     *      
     */
    public function getReglas(Request $request): Response
    {        
      $reglas = $this->getDoctrine()->getRepository(Regla::class)->findAll();

      $serializer = $this->get('jms_serializer'); 
    
      return new Response($serializer->serialize($reglas, "json"), 200);
    }

    /**
     * @Route("/show", name="regla_show", methods={"GET"})
     * @SWG\Response(response=200, description="Regla del sistema")
     * @SWG\Tag(name="Regla")
     * @QueryParam(name="id", strict=true, nullable=false, allowBlank=false, description="Regla Id")
     *      
     */
    public function show(Request $request, ParamFetcher $pf): Response
    {        

      $entityManager = $this->getDoctrine()->getManager();

      $regla = $entityManager->getRepository(Regla::class)->find($pf->get('id'));

      if (!$regla) {

        $error = [ 
          "message" => "No se encontró la regla",
          "title" => "Regla inexistente",
        ];

        return new Response($serializer->serialize($error, "json"), 400);

      }

      $serializer = $this->get('jms_serializer'); 
     
      return new Response($serializer->serialize($regla, "json"), 200);

    }

    /**
     * @Route("/delete", name="regla_delete", methods={"POST"})
     * @SWG\Response(response=200, description="Regla del sistema")
     * @SWG\Tag(name="Regla")
     * @QueryParam(name="id", strict=true, nullable=false, allowBlank=false, description="Regla Id")
     *      
     */
    public function delete(Request $request, ParamFetcher $pf): Response
    {        

      $entityManager = $this->getDoctrine()->getManager();

      $regla = $entityManager->getRepository(Regla::class)->find($pf->get('id'));

      if (!$regla) {

        $error = [ 
          "message" => "No se encontró la regla",
          "title" => "Regla inexistente",
        ];

        return new Response($serializer->serialize($error, "json"), 400);

      }

      $entityManager->remove($regla);
      $entityManager->flush();

      $serializer = $this->get('jms_serializer'); 
     
      return new Response($serializer->serialize($regla, "json"), 200);

    }

    /**
     * @Route("/new", name="regla_new", methods={"POST"})
     * @SWG\Response(response=200, description="Regla creada exitosamente")
     * @SWG\Tag(name="Regla")
     * @RequestParam(name="evento", strict=true, nullable=false, allowBlank=false, description="Evento")
     * @RequestParam(name="expresion", strict=true, nullable=false, allowBlank=false, description="Expresion")
     * @RequestParam(name="accion", strict=true, nullable=false, allowBlank=false, description="Acción")
     * 
     * @param ParamFetcher $pf
     */
    public function new(Request $request, ParamFetcher $pf): Response
    {

      $serializer = $this->get('jms_serializer'); 

      try {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->getConnection()->beginTransaction();

        $regla= new Regla($pf->get('evento'),$pf->get('expresion'),$pf->get('accion'));
      
        $entityManager->persist($regla);

        $entityManager->flush();
        $entityManager->getConnection()->commit();
        
      } catch (\Throwable $th) {

        $entityManager->getConnection()->rollBack();

        $error = [ 
          "message" => "Se produjo un error al intentar crear la regla",
        ];
  
        return new Response($serializer->serialize($error, "json"), 500);

      }
      
      return new Response($serializer->serialize($regla, "json"), 200);
     
    }

    /**
     * @Route("/update/{id}", name="regla_update", methods={"POST"})
     * @SWG\Response(response=200, description="Regla actualizada exitosamente")
     * @SWG\Tag(name="Regla")
     * @RequestParam(name="evento", strict=true, nullable=false, allowBlank=false, description="Evento")
     * @RequestParam(name="expresion", strict=true, nullable=false, allowBlank=false, description="Expresion")
     * @RequestParam(name="accion", strict=true, nullable=false, allowBlank=false, description="Acción")
     * 
     * @param ParamFetcher $pf
     */
    public function update(Request $request, ParamFetcher $pf, $id): Response
    {

      $serializer = $this->get('jms_serializer'); 

      $entityManager = $this->getDoctrine()->getManager();
      $regla = $entityManager->getRepository(Regla::class)->find($id);

      if (!$regla) {

        $error = [ 
          "message" => "No se encontró la regla",
          "title" => "Regla inexistente",
        ];

        return new Response($serializer->serialize($error, "json"), 400);

      }

      try {

        $entityManager->getConnection()->beginTransaction();

        $regla->setEvento($pf->get('evento'));
        $regla->setExpresion($pf->get('expresion'));
        $regla->setAccion($pf->get('accion'));
        
        $entityManager->flush();
        $entityManager->getConnection()->commit();
        
      } catch (\Throwable $th) {

        $entityManager->getConnection()->rollBack();

        $error = [ 
          "message" => "Se produjo un error al intentar actualizar la regla",
        ];
  
        return new Response($serializer->serialize($error, "json"), 500);

      }
      
      return new Response($serializer->serialize($regla, "json"), 200);
     
    }


}
