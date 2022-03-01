<?php

namespace App\Extensions;

use App\Entity\Regla;
use App\Extensions\SistemaAvisos;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

trait SistemaReglas
{
    private $expressionLanguage;
    public function __construct() { 
        $this->expressionLanguage = new ExpressionLanguage();
    }

    public function evaluar(string $evento, array $datos)
    {
        $reglas = $this->getDoctrine()->getRepository(Regla::class)->findBy(["evento" => $evento]);
        $sa = new SistemaAvisos($this->getDoctrine()->getManager());
        foreach ($reglas as $regla){
            $resultado = $this->expressionLanguage->evaluate($regla->getExpresion(),$datos);
            if($resultado){
                $datosAccion = $datos;
                $datosAccion["aviso"] = $sa;
                $datosAccion["evento"] = $evento;
                $this->expressionLanguage->evaluate($regla->getAccion(),$datosAccion);
            }
        }
    }



}