<?php

namespace App\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Paciente;
use App\Entity\UserPaciente;
use App\Entity\User;
use App\Entity\Sistema;
use App\Entity\Internacion;
use App\Entity\InternacionCama;
use App\Entity\Sala;
use App\Entity\Cama;
use FOS\RestBundle\Controller\Annotations as Rest;
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
use JMS\Serializer\SerializationContext;

/**
 * Class Paciente Controller
 *
 * @Route("/api/paciente")
 */
class PacienteController extends FOSRestController
{

    /**
     * @Route("/internados", name="pacientes_internados", methods={"GET"})
     * @SWG\Response(response=200, description="Listado de pacientes internados")
     * @SWG\Tag(name="Paciente")
     * @QueryParam(name="sistema", strict=false, nullable=true, allowBlank=false, description="Sistema Id")
     * @QueryParam(name="sala", strict=false, nullable=true, allowBlank=false, description="Sala Id")
     *      
     * @param ParamFetcher $pf
     */
    public function getPacientesInternados(Request $request, ParamFetcher $pf): Response
    {
        //si se recibe un sistemaId como parámetro se utiliza ese, 
        //sinó se utiliza el id del sistema al que pertenece el usuario.
        $sistema = $pf->get('sistema') ? $pf->get('sistema') : $this->getUser()->getSistema()->getId();
        $sala = $pf->get('sala') ? $pf->get('sala') : null;
        $pacientes = $this->getDoctrine()->getRepository(Paciente::class)->findAllPacientesInternados($sistema, $sala);       
        
        return new JsonResponse($pacientes, 200);
    }

    /**
     * @Route("/egresados", name="pacientes_egresados", methods={"GET"})
     * @SWG\Response(response=200, description="Listado de pacientes egresados")
     * @SWG\Tag(name="Paciente")
     *      
     */
    public function getPacientesEgresados(Request $request): Response
    {        
        $pacientes = $this->getDoctrine()->getRepository(Paciente::class)->findAllPacientesEgresados();        
        return new JsonResponse($pacientes, 200);
    }

    /**
     * @Route("/fallecidos", name="pacientes_fallecidos", methods={"GET"})
     * @SWG\Response(response=200, description="Listado de pacientes egresados")
     * @SWG\Tag(name="Paciente")
     *      
     */
    public function getPacientesFallecidos(Request $request): Response
    {        
        $pacientes = $this->getDoctrine()->getRepository(Paciente::class)->findAllPacientesFallecidos();        
        return new JsonResponse($pacientes, 200);
    }

    /**
     * @Route("/getPaciente", name="paciente_show", methods={"GET"})
     * @SWG\Response(response=200, description="Paciente")
     * @SWG\Tag(name="Paciente")
     * @QueryParam(name="id", strict=true, nullable=false, allowBlank=false, description="Paciente Id")
     *      
     * @param ParamFetcher $pf
     */
    public function getPaciente(Request $request, ParamFetcher $pf): Response
    {

      $serializer = $this->get('jms_serializer'); 
      $paciente = $this->getDoctrine()->getRepository(Paciente::class)->findPaciente($pf->get('id'));
      if (!$paciente) {

        $error = [ 
          "message" => "El paciente id ".$pf->get('id')." no existe",
          "title" => "No se encontró el paciente",
          "relocate" => "go back",
        ];
  
        return new Response($serializer->serialize($error, "json"), 404);
      }

      return new Response($serializer->serialize($paciente, "json"), 200);
    }

    /**
     * @Route("/new", name="paciente_new", methods={"POST"})
     * @SWG\Response(response=200, description="Paciente creado exitosamente")
     * @SWG\Tag(name="Paciente")
     * @RequestParam(name="dni", strict=true, nullable=false, allowBlank=false, description="Dni")
     * @RequestParam(name="apellido", strict=true, nullable=false, allowBlank=false, description="Apellido")
     * @RequestParam(name="nombre", strict=true, nullable=false, allowBlank=false, description="Nombre")
     * @RequestParam(name="direccion", strict=true, nullable=false, allowBlank=false, description="Dirección")
     * @RequestParam(name="telefono", strict=true, nullable=false, allowBlank=false, description="Teléfono")
     * @RequestParam(name="email", strict=true, nullable=false, allowBlank=false, description="Email")
     * @RequestParam(name="fecha_nacimiento", strict=true, nullable=false, allowBlank=false, description="Fecha de nacimiento.")
     * @RequestParam(name="obra_social", strict=true, nullable=true, allowBlank=true, description="Obra Social")
     * @RequestParam(name="antecedentes", strict=true, nullable=true, allowBlank=true, description="Antecedentes")
     * @RequestParam(name="contacto_nombre", strict=false, description="Nombre de algún contacto")
     * @RequestParam(name="contacto_apellido", strict=false, description="Apellido de algún contacto")
     * @RequestParam(name="contacto_telefono", strict=false, description="Teléfono de algún contacto")
     * @RequestParam(name="contacto_parentesco", strict=false, description="Parentesco del contacto")
     * 
     * @param ParamFetcher $pf
     */
    public function new(Request $request, ParamFetcher $pf): Response
    {

      $dni = (int)$pf->get('dni');

      $serializer = $this->get('jms_serializer'); 

      if ($this->getDoctrine()->getRepository(Paciente::class)->findBy(["dni" => $dni])) {

        $error = [ 
          "message" => "El paciente con dni: ".$dni." ya se encuentra cargado en el sistema",
          "title" => "Paciente ya existente",
        ];
        return new Response($serializer->serialize($error, "json"), 400);

      }

      try {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->getConnection()->beginTransaction();

        $paciente= new Paciente($dni,
                              $pf->get('apellido'),
                              $pf->get('nombre'),
                              $pf->get('direccion'),
                              $pf->get('telefono'),
                              $pf->get('email'),
                              date_create($pf->get('fecha_nacimiento')),
                              $pf->get('obra_social'),
                              $pf->get('antecedentes'),
                              $pf->get('contacto_nombre') ? $pf->get('contacto_nombre') : null,
                              $pf->get('contacto_apellido') ? $pf->get('contacto_apellido') : null,
                              $pf->get('contacto_telefono') ? $pf->get('contacto_telefono') : null,
                              $pf->get('contacto_parentesco') ? $pf->get('contacto_parentesco') : null);
      
        $entityManager->persist($paciente);

        $entityManager->flush();
        $entityManager->getConnection()->commit();
        
      } catch (\Exception $e) {

        $entityManager->getConnection()->rollBack();

        $error = [ 
          "message" => "Se produjo un error al intentar crear el paciente: ".$e->getMessage(),
        ];
  
        return new Response($serializer->serialize($error, "json"), 500);

      }
      
      return new Response($serializer->serialize($paciente, "json"), 200);
     
    }

    /**
     * @Route("/update/{id}", name="paciente_update", methods={"POST"})
     * @SWG\Response(response=200, description="Paciente actualizado exitosamente")
     * @SWG\Tag(name="Paciente")
     * @RequestParam(name="dni", strict=true, nullable=false, allowBlank=false, description="Dni")
     * @RequestParam(name="apellido", strict=true, nullable=false, allowBlank=false, description="Apellido")
     * @RequestParam(name="nombre", strict=true, nullable=false, allowBlank=false, description="Nombre")
     * @RequestParam(name="direccion", strict=true, nullable=false, allowBlank=false, description="Dirección")
     * @RequestParam(name="telefono", strict=true, nullable=false, allowBlank=false, description="Teléfono")
     * @RequestParam(name="email", strict=true, nullable=false, allowBlank=false, description="Email")
     * @RequestParam(name="fecha_nacimiento", strict=true, nullable=false, allowBlank=false, description="Fecha de nacimiento.")
     * @RequestParam(name="obra_social", strict=true, nullable=true, allowBlank=true, description="Obra Social")
     * @RequestParam(name="antecedentes", strict=true, nullable=true, allowBlank=true, description="Antecedentes")
     * @RequestParam(name="contacto_nombre", strict=false, description="Nombre de algún contacto")
     * @RequestParam(name="contacto_apellido", strict=false, description="Apellido de algún contacto")
     * @RequestParam(name="contacto_telefono", strict=false, description="Teléfono de algún contacto")
     * @RequestParam(name="contacto_parentesco", strict=false, description="Parentesco del contacto")
     * 
     * @param ParamFetcher $pf
     */
    public function update(Request $request, ParamFetcher $pf): Response
    {

      $serializer = $this->get('jms_serializer'); 
      $dni = (int)$pf->get('dni');
      $entityManager = $this->getDoctrine()->getManager();
      $paciente = $entityManager->getRepository(Paciente::class)->findBy(["dni" => $dni]);

      if (!$paciente) {

        $error = [ 
          "message" => "No se encontró el paciente con dni: ".$dni,
          "title" => "Paciente inexistente",
        ];

        return new Response($serializer->serialize($error, "json"), 400);

      }

      try {

        $entityManager->getConnection()->beginTransaction();

        $paciente[0]->setApellido($pf->get('apellido'));
        $paciente[0]->setNombre($pf->get('nombre'));
        $paciente[0]->setDireccion($pf->get('direccion'));
        $paciente[0]->setTelefono($pf->get('telefono'));
        $paciente[0]->setEmail($pf->get('email'));
        $paciente[0]->setFechaNacimiento(date_create($pf->get('fecha_nacimiento')));
        $paciente[0]->setObraSocial($pf->get('obra_social'));
        $paciente[0]->setAntecedentes($pf->get('antecedentes'));
        $paciente[0]->setContactoNombre($pf->get('contacto_nombre'));
        $paciente[0]->setContactoApellido($pf->get('contacto_apellido'));
        $paciente[0]->setContactoTelefono($pf->get('contacto_telefono'));
        $paciente[0]->setContactoParentesco($pf->get('contacto_parentesco'));
        $entityManager->flush();
        $entityManager->getConnection()->commit();
        
      } catch (\Throwable $th) {

        $entityManager->getConnection()->rollBack();

        $error = [ 
          "message" => "Se produjo un error al intentar actualizar el paciente",
        ];
  
        return new Response($serializer->serialize($error, "json"), 500);

      }
      
      return new Response("Paciente actualizado", 200);
     
    }

    /**
     * @Route("/asignarMedico", name="asignar_medico", methods={"POST"})
     * @SWG\Response(response=200, description="Asignar médico a paciente")
     * @SWG\Tag(name="Paciente")
     * @RequestParam(name="pacienteId", strict=true, nullable=false, allowBlank=false, description="Paciente Id")
     * @RequestParam(name="medicoId", strict=true, nullable=false, allowBlank=false, description="Médico Id")
     * 
     * @param ParamFetcher $pf
     */
    public function asignarMedico(Request $request, ParamFetcher $pf): Response
    {
      $entityManager = $this->getDoctrine()->getManager();

      $pacienteId = $pf->get('pacienteId');
      $medicoId = $pf->get('medicoId');

      $paciente = $this->getDoctrine()->getRepository(Paciente::class)->find($pacienteId);
      $medico = $this->getDoctrine()->getRepository(User::class)->find($medicoId);

      $yaExisteLaRelacion = $this->getDoctrine()->getRepository(UserPaciente::class)->findBy(['user' => $medico, 
                                                                                              'paciente' => $paciente,
                                                                                              'fecha_hasta' => null]);
      
      $serializer = $this->get('jms_serializer'); 

      if ($yaExisteLaRelacion) {

        $error = [
          "message" => "Ese médico ya se encuentra asignado al paciente",
        ];
        
        return new Response($serializer->serialize($error, "json"), 400);
        
      }

      $relacionConJefeDeSistema = $this->getDoctrine()->getRepository(UserPaciente::class)
                                       ->findOneBy(['user' => $this->getUser()->getId(),
                                                 'paciente' => $paciente,
                                                 'fecha_hasta' => null]);

      //si el paciente sigue relacionado al jefe del sistema hay que desvincularlo.
      if ($relacionConJefeDeSistema) {

        $relacionConJefeDeSistema->setFechaHasta(new \DateTime());
        $entityManager->persist($relacionConJefeDeSistema);

      }

      $userPaciente= new UserPaciente($paciente, $medico);
      
      $entityManager->persist($userPaciente);
      $entityManager->flush();

      return new Response($serializer->serialize($userPaciente, "json",SerializationContext::create()->enableMaxDepthChecks()), 200);
     
    }

    /**
     * @Route("/desasignarMedico", name="desasignar_medico", methods={"POST"})
     * @SWG\Response(response=200, description="Desasignar médico a paciente")
     * @SWG\Tag(name="Paciente")
     * @RequestParam(name="pacienteId", strict=true, nullable=false, allowBlank=false, description="Paciente Id")
     * @RequestParam(name="medicoId", strict=true, nullable=false, allowBlank=false, description="Médico Id")
     * 
     * @param ParamFetcher $pf
     */
    public function desasignarMedico(Request $request, ParamFetcher $pf): Response
    {
      $entityManager = $this->getDoctrine()->getManager();

      $paciente = $this->getDoctrine()->getRepository(Paciente::class)->find($pf->get('pacienteId'));
      $medico = $this->getDoctrine()->getRepository(User::class)->find($pf->get('medicoId'));
  
      $userPaciente = $this->getDoctrine()->getRepository(UserPaciente::class)->findOneBy(['user' => $medico, 
                                                                                           'paciente' => $paciente,
                                                                                           'fecha_hasta' => null]);
      
      if ($userPaciente) {

        $userPaciente->setFechaHasta(new \DateTime());
        $entityManager->persist($userPaciente);
        $entityManager->flush();

      } else {
        
        $error = [
          "message" => "No existe la relación entre ese paciente y ese médico",
        ];
        
        return new Response($serializer->serialize($error, "json"), 400);

      }

      $serializer = $this->get('jms_serializer');

      return new Response($serializer->serialize($userPaciente, "json",SerializationContext::create()->enableMaxDepthChecks()), 200);

    }

    /**
     * @Route("/existsWithDni", name="exists_with_dni", methods={"POST"})
     * @SWG\Response(response=200, description="Existe o no existe un paciente con un dni especifico")
     * @SWG\Tag(name="Paciente")
     * @RequestParam(name="dni", strict=true, nullable=false, allowBlank=false, description="Dni")
     * 
     * @param ParamFetcher $pf
     */
    public function existsWithDni(Request $request, ParamFetcher $pf): Response
    {
      $serializer = $this->get('jms_serializer'); 
      $dni = (int)$pf->get('dni');
      if ($this->getDoctrine()->getRepository(Paciente::class)->findBy(["dni" => $dni])) {
        $response = [ 
          "title" => "Paciente existente",
          "message" => "El paciente con dni: ".$dni." ya se encuentra cargado en el sistema",
          "exists" => true,
          "dni" => $dni,
        ];
        return new Response($serializer->serialize($response, "json"), 200);

      }else{
        $response = [ 
          "title" => "Paciente inexistente",
          "message" => "El paciente con dni: ".$dni." no se encuentra cargado en el sistema",
          "exists" => false,
          "dni" => $dni,
        ];
        return new Response($serializer->serialize($response, "json"), 200);
      }

    }

  /**
   * @Route("/cambiarDeSistema", name="sistema_cambiar", methods={"POST"})
   * @SWG\Response(response=200, description="Cambiar de sistema al paciente")
   * @RequestParam(name="sistemaDestinoId", strict=true, nullable=false, allowBlank=false, description="Id del sistema destino")
   * @RequestParam(name="internacionId", strict=true, nullable=false, allowBlank=false, description="Id de la internación")
   * @SWG\Tag(name="Cambiar de Sistema")
   *      
   * @param ParamFetcher $pf
   */
  public function cambiarDeSistema(Request $request, ParamFetcher $pf): Response
  {
    
    $serializer = $this->get('jms_serializer');

    $internacionId = $pf->get('internacionId');
    
    $internacionActual = $this->getDoctrine()->getRepository(Internacion::class)->find($internacionId);
    if (!$internacionActual) {

      $error = [ 
        "message" => "La internación id '.$internacionId.' no existe",
      ];

      return new Response($serializer->serialize($error, "json"), 404);
    }

    $sistemaDestino = $this->getDoctrine()->getRepository(Sistema::class)->find($pf->get('sistemaDestinoId'));
    if ($sistemaDestino->getCamasDisponibles() == 0) {

      $error = [ 
        "message" => "No hay camas disponibles en el sistema destino",
      ];

      return new Response($serializer->serialize($error, "json"), 400);
    }

    try {
      
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->getConnection()->beginTransaction();
  
      //a partir de acá seteo todo lo del sistema destino
      if ($sistemaDestino->getNombre() == 'DOMICILIO') {
  
        //si el destino es domicilio creo una cama nueva para la única sala que hay para DOMICILIO y le pongo estado 'ocupada'.
        $salaDomicilio = $this->getDoctrine()->getRepository(Sala::class)->findOneBy(['sistema' => $sistemaDestino]);

        if (!$salaDomicilio) {

          $error = [ 
            "message" => "Se produjo un error al intentar el cambio de sistema: no hay sala para el sistema Domicilio",
          ];
    
          return new Response($serializer->serialize($error, "json"), 400);
        }

        $cama = new Cama();
        $cama->setSala($salaDomicilio);
        $cama->setEstado('ocupada');

        //algo hay que poner (la columna es not null)
        $cama->setNumero(1);
    
      } else {
  
        //si el destino no es domicilio busco la 1er cama libre que haya y le pongo ocupada.
        //seteo los nros.del sistema destino.
        $cama = $this->getDoctrine()->getRepository(Cama::class)->findPrimerCamaLibre($sistemaDestino->getId());
        $cama->setEstado('ocupada');
        
        $sistemaDestino->setCamasDisponibles($sistemaDestino->getCamasDisponibles() - 1);
        $sistemaDestino->setCamasOcupadas($sistemaDestino->getCamasOcupadas() + 1);
      
      }
  
      //esto es igual para cualquier sistema destino, ya sea Domicilo u otro.
      $internacionCamaNueva = new InternacionCama();
      $internacionCamaNueva->setInternacion($internacionActual);
      $internacionCamaNueva->setCama($cama);

      $entityManager->persist($cama);
      $entityManager->persist($internacionCamaNueva);
      //hasta acá todo lo del sistema destino
  
      //a partir de acá modifico todo lo del sistema Origen
      $sistemaOrigen = $this->getUser()->getSistema();
  
      //si el origen no es domicilio seteo los nros.del sistema.
      if ($sistemaOrigen->getNombre() != 'DOMICILIO') {
  
        $sistemaOrigen->setCamasDisponibles($sistemaOrigen->getCamasDisponibles() + 1);
        $sistemaOrigen->setCamasOcupadas($sistemaOrigen->getCamasOcupadas() - 1);
      
      }
  
      $internacionCamaActual = $this->getDoctrine()->getRepository(InternacionCama::class)
                        ->findBy(["internacion" => $internacionActual, "fecha_hasta" => null])[0];
  
      $internacionCamaActual->setFechaHasta(new \DateTime());
      $internacionCamaActual->getCama()->setEstado('libre');
      //hasta acá todo lo del Origen

      $paciente = $internacionActual->getPaciente();
      //desasignar todos los médicos para ese paciente en el sistema origen.
      $usersPacientes = $this->getDoctrine()->getRepository(UserPaciente::class)
                             ->findBy(["paciente" => $paciente, "fecha_hasta" => null]);

      if ($usersPacientes) {

        foreach ($usersPacientes as $userPaciente) {
          $userPaciente->setFechaHasta(new \DateTime);
          $entityManager->persist($userPaciente);
        }

      }

      //busco al jefe del sistema para asignárselo al paciente (para las alertas...)
      $users = $this->getDoctrine()->getRepository(User::class)
                    ->findBy(["sistema" => $sistemaDestino->getId()]);

      foreach ($users as $user) {
        if (in_array("ROLE_JEFE", $user->getRoles())) {
          $jefeSistema = $user;
          break;
        };
      }

      $jefePaciente = new UserPaciente($paciente, $jefeSistema);
      $entityManager->persist($jefePaciente);
  
      $entityManager->flush();
      $entityManager->getConnection()->commit();

    } catch (\Exception $e) {

      $entityManager->getConnection()->rollBack();

      $error = [ 
        "message" => "Se produjo un error al intentar el cambio de sistema".$e->getMessage(),
      ];

      return new Response($serializer->serialize($error, "json"), 500);

    } 

    return new Response("Cambio realizado", 200);

  }

}
