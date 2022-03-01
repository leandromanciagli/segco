<?php

namespace App\Entity;

use App\Repository\ReglaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReglaRepository::class)
 */
class Regla
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $evento;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $expresion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $accion;

    public function __construct($evento, 
                                $texto, 
                                $accion
    )     
    {

        $this->evento = $evento;
        $this->expresion = $texto;
        $this->accion = $accion;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvento(): ?string
    {
        return $this->evento;
    }

    public function setEvento(string $evento): self
    {
        $this->evento = $evento;

        return $this;
    }

    public function getExpresion(): ?string
    {
        return $this->expresion;
    }

    public function setExpresion(string $expresion): self
    {
        $this->expresion = $expresion;

        return $this;
    }

    public function getAccion(): ?string
    {
        return $this->accion;
    }

    public function setAccion(string $accion): self
    {
        $this->accion = $accion;

        return $this;
    }
}
