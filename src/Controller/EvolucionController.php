<?php

namespace App\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Paciente;
use App\Entity\Internacion;
use App\Entity\Evolucion;
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
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints;
use App\Extensions\SistemaReglas;
use JMS\Serializer\SerializationContext;

/**
 * Class Evolución Controller
 *
 * @Route("/api/evolucion")
 */
class EvolucionController extends FOSRestController
{
  use SistemaReglas;

  /**
   * @Route("/index", name="evoluciones", methods={"GET"})
   * @SWG\Response(response=200, description="Evoluciones de una internación")
   * @SWG\Tag(name="Evolución")
   * @QueryParam(name="id", strict=true, nullable=false, allowBlank=false, description="Internación Id")
   *      
   * @param ParamFetcher $pf
   */
  public function getEvoluciones(Request $request, ParamFetcher $pf): Response
  {
    $evoluciones = $this->getDoctrine()->getRepository(Evolucion::class)->findAllEvoluciones($pf->get('id'));

    $serializer = $this->get('jms_serializer');
    
    $result = $evoluciones ? $serializer->serialize($evoluciones, "json") : null;
    return new Response($result, 200);
  }

  /**
   * @Route("/show", name="evolucion_show", methods={"GET"})
   * @SWG\Response(response=200, description="Ver Evolución")
   * @SWG\Tag(name="Evolución")
   * @QueryParam(name="id", strict=true, nullable=false, allowBlank=false, description="Evolución Id")
   *      
   * @param ParamFetcher $pf
   */
  public function getEvolucion(Request $request, ParamFetcher $pf): Response
  {
    $evolucion = $this->getDoctrine()->getRepository(Evolucion::class)->find($pf->get('id'));    

    $serializer = $this->get('jms_serializer');    
    
    return new Response($serializer->serialize($evolucion, "json", SerializationContext::create()->enableMaxDepthChecks()), 200);
  }

  /**
   * @Route("/ultima", name="evolucion_ultima", methods={"GET"})
   * @SWG\Response(response=200, description="Ver Evolución")
   * @SWG\Tag(name="Evolución")
   * @QueryParam(name="internacionId", strict=true, nullable=false, allowBlank=false, description="Internación Id")
   *      
   * @param ParamFetcher $pf
   */
  public function getUltimaEvolucion(Request $request, ParamFetcher $pf): Response
  {
    $evolucion = $this->getDoctrine()->getRepository(Evolucion::class)->findUltimaEvolucion($pf->get('internacionId'));    

    $serializer = $this->get('jms_serializer');

    $result = $evolucion ? $serializer->serialize($evolucion, "json") : null;
    
    return new Response($result, 200);
  }

  /**
   * @Route("/new", name="evolucion_new", methods={"POST"})
   * @SWG\Response(response=200, description="Evolución creada exitosamente")
   * @SWG\Tag(name="Evolución")
   * @RequestParam(name="internacion_id", strict=true, nullable=false, allowBlank=false, description="internacionId")
   * @RequestParam(name="temperatura", strict=true, nullable=false, allowBlank=false, description="temperatura")
   * @RequestParam(name="ta_sistolica", strict=true, nullable=false, allowBlank=false, description="Tensión Arterial Sistólica")
   * @RequestParam(name="ta_diastolica", strict=true, nullable=false, allowBlank=false, description="Tensión Arterial Diastólica")
   * @RequestParam(name="frecuencia_cardiaca", strict=true, nullable=false, allowBlank=false, description="Frecuencia cardiaca")
   * @RequestParam(name="frecuencia_respiratoria", strict=true, nullable=false, allowBlank=false, description="Frecuencia respiratoria")
   * @RequestParam(name="mecanica_ventilatoria", strict=true, nullable=false, allowBlank=false, description="Mecánica ventilatoria")
   * @RequestParam(name="canula_nasal_valor", strict=true, nullable=true, allowBlank=false, description="Cánula nasal valor")
   * @RequestParam(name="mascara_reservorio_valor", strict=true, nullable=true, allowBlank=false, description="Máscara reservorio valor")
   * @RequestParam(name="saturacion_oxigeno", strict=true, nullable=true, allowBlank=false, description="Saturación oxígeno")
   * @RequestParam(name="pafi", strict=true, nullable=true, allowBlank=false, description="PaFi")
   * @RequestParam(name="prono_vigil", strict=true, nullable=true, allowBlank=true, description="Prono vigil")
   * @RequestParam(name="tos", strict=true, nullable=true, allowBlank=true, description="Tos") 
   * @RequestParam(name="disnea", strict=true, nullable=true, allowBlank=true, description="Disnea")
   * @RequestParam(name="estabilidad_sintomas_respiratorios", strict=true, nullable=true, allowBlank=true, description="Estabilidad/Desaparición síntomas respiratorios")
   * @RequestParam(name="somnolencia", strict=true, nullable=false, allowBlank=true, description="Somnolencia")
   * @RequestParam(name="anosmia", strict=true, nullable=false, allowBlank=true, description="Anosmia")   
   * @RequestParam(name="disgeusia", strict=true, nullable=false, allowBlank=true, description="Disgeusia")
   * @RequestParam(name="rx_tx_tipo", strict=true, nullable=true, allowBlank=true, description="Radiografía Tórax Tipo")
   * @RequestParam(name="rx_tx_descrip", strict=true, nullable=true, allowBlank=true, description="Radiografía Tórax descripción")
   * @RequestParam(name="tac_torax_tipo", strict=true, nullable=true, allowBlank=true, description="TAC tórax tipo")
   * @RequestParam(name="tac_torax_descrip", strict=true, nullable=true, allowBlank=true, description="TAC tóorax decripción")
   * @RequestParam(name="ecg_tipo", strict=true, nullable=true, allowBlank=true, description="ECG tipo")
   * @RequestParam(name="ecg_descrip", strict=true, nullable=true, allowBlank=true, description="ECG descripción")
   * @RequestParam(name="pcr_covid_tipo", strict=true, nullable=true, allowBlank=true, description="PCR Covid tipo")
   * @RequestParam(name="pcr_covid_descrip", strict=true, nullable=true, allowBlank=true, description="PCR Covid descripción")
   * @RequestParam(name="observacion", strict=true, nullable=true, allowBlank=true, description="Observaciones")
   *
   *  @param ParamFetcher $pf
   */
  public function new(Request $request, ParamFetcher $pf): Response
  {
    $serializer = $this->get('jms_serializer');    

    $internacion = $this->getDoctrine()->getRepository(Internacion::class)->find($pf->get('internacion_id'));

    if (!$internacion) {

      $error = [ 
        "message" => "La internación id ".$pf->get('internacion_id')." no existe",
      ];

      return new Response($serializer->serialize($error, "json"), 404);

    }

    try {

      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->getConnection()->beginTransaction();

      $fecha_carga = new \DateTime();

      $evolucion = new Evolucion();
      $evolucion->setInternacion($internacion);
      $evolucion->setFechaCarga($fecha_carga);
      $evolucion->setTemperatura($pf->get('temperatura'));
      $evolucion->setTensionArterialSistolica((int)$pf->get('ta_sistolica'));
      $evolucion->setTensionArterialDiastolica((int)$pf->get('ta_diastolica'));
      $evolucion->setFrecuenciaCardiaca((int)$pf->get('frecuencia_cardiaca'));
      $evolucion->setFrecuenciaRespiratoria((int)$pf->get('frecuencia_respiratoria'));
      $evolucion->setMecanicaVentilatoria($pf->get('mecanica_ventilatoria'));
      $evolucion->setCanulaNasalOxigeno($pf->get('canula_nasal_valor'));
      $evolucion->setMascaraConReservorio($pf->get('mascara_reservorio_valor'));
      $evolucion->setSaturacionOxigeno($pf->get('saturacion_oxigeno'));
      $evolucion->setPafi($pf->get('pafi'));
      $evolucion->setPronoVigil($pf->get('prono_vigil'));
      $evolucion->setTos($pf->get('tos'));
      $evolucion->setDisnea($pf->get('disnea'));
      $evolucion->setEstabilidadDesaparicionSintomasResp($pf->get('estabilidad_sintomas_respiratorios'));
      $evolucion->setSomnolencia($pf->get('somnolencia'));
      $evolucion->setAnosmia($pf->get('anosmia'));
      $evolucion->setDisgeusia($pf->get('disgeusia'));
      $evolucion->setRadiografiaTipo($pf->get('rx_tx_tipo'));
      $evolucion->setRadiografiaDescrip($pf->get('rx_tx_descrip'));
      $evolucion->setTomografiaToraxTipo($pf->get('tac_torax_tipo'));
      $evolucion->setTomografiaToraxDescrip($pf->get('tac_torax_descrip'));
      $evolucion->setElectrocardiogramaTipo($pf->get('ecg_tipo'));
      $evolucion->setElectrocardiogramaDescrip($pf->get('ecg_descrip'));
      $evolucion->setPcrCovidTipo($pf->get('pcr_covid_tipo'));
      $evolucion->setPcrCovidDescrip($pf->get('pcr_covid_descrip'));
      $evolucion->setObservacion($pf->get('observacion'));
      $evolucion->setSistema($this->getUser()->getSistema()->getId());

      $entityManager->persist($evolucion);
      $entityManager->flush();

      $this->evaluar('NUEVA EVOLUCION',['evolucion' => $evolucion, 'paciente' => $internacion->getPaciente(), 'detalle' => "/"."verPaciente/".$internacion->getPaciente()->getId()]);
      $entityManager->getConnection()->commit();
      
    } catch (\Throwable $th) {

      $entityManager->getConnection()->rollBack();

      $error = [ 
        "message" => "Se produjo un error al intentar crear la evolución".$th->getMessage(),
      ];

      return new Response($serializer->serialize($error, "json"), 500);

    }
    return new Response('Evolución creada', 200);
    
  }

}
