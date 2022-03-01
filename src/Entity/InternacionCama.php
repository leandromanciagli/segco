<?php

namespace App\Entity;

use App\Repository\InternacionCamaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InternacionCamaRepository::class)
 */
class InternacionCama
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Internacion::class, inversedBy="internacionCamas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $internacion;

    /**
     * @ORM\ManyToOne(targetEntity=Cama::class, inversedBy="internacionCamas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cama;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_desde;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_hasta;

    public function __construct()
    {
        $this->fecha_desde = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInternacion(): ?Internacion
    {
        return $this->internacion;
    }

    public function setInternacion(?Internacion $internacion): self
    {
        $this->internacion = $internacion;

        return $this;
    }

    public function getCama(): ?Cama
    {
        return $this->cama;
    }

    public function setCama(?Cama $cama): self
    {
        $this->cama = $cama;

        return $this;
    }

    public function getFechaDesde(): ?\DateTimeInterface
    {
        return $this->fecha_desde;
    }

    public function setFechaDesde(\DateTimeInterface $fecha_desde): self
    {
        $this->fecha_desde = $fecha_desde;

        return $this;
    }

    public function getFechaHasta(): ?\DateTimeInterface
    {
        return $this->fecha_hasta;
    }

    public function setFechaHasta(?\DateTimeInterface $fecha_hasta): self
    {
        $this->fecha_hasta = $fecha_hasta;

        return $this;
    }
}
