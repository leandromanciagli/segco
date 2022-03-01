<?php

namespace App\Entity;

use App\Repository\InternacionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InternacionRepository::class)
 */
class Internacion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Paciente::class, inversedBy="internaciones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $paciente;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sintomas;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_inicio_sintomas;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_diagnostico;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_carga;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_egreso;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_obito;

     /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $motivo_egreso;

    /**
     * @ORM\OneToMany(targetEntity=InternacionCama::class, mappedBy="internacion")
     */
    private $internacionCamas;

    /**
     * @ORM\OneToMany(targetEntity=Evolucion::class, mappedBy="internacion")
     */
    private $evoluciones;

    public function __construct()
    {
        $this->fecha_carga = new \DateTime();
        $this->internacionCamas = new ArrayCollection();
        $this->evoluciones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaciente(): ?Paciente
    {
        return $this->paciente;
    }

    public function setPaciente(?Paciente $paciente): self
    {
        $this->paciente = $paciente;

        return $this;
    }

    public function getSintomas(): ?string
    {
        return $this->sintomas;
    }

    public function setSintomas(string $sintomas): self
    {
        $this->sintomas = $sintomas;

        return $this;
    }

    public function getFechaInicioSintomas(): ?\DateTimeInterface
    {
        return $this->fecha_inicio_sintomas;
    }

    public function setFechaInicioSintomas(\DateTimeInterface $fecha_inicio_sintomas): self
    {
        $this->fecha_inicio_sintomas = $fecha_inicio_sintomas;

        return $this;
    }

    public function getFechaDiagnostico(): ?\DateTimeInterface
    {
        return $this->fecha_diagnostico;
    }

    public function setFechaDiagnostico(?\DateTimeInterface $fecha_diagnostico): self
    {
        $this->fecha_diagnostico = $fecha_diagnostico;

        return $this;
    }

    public function getFechaCarga(): ?\DateTimeInterface
    {
        return $this->fecha_carga;
    }

    public function setFechaCarga(\DateTimeInterface $fecha_carga): self
    {
        $this->fecha_carga = $fecha_carga;

        return $this;
    }

    public function getFechaEgreso(): ?\DateTimeInterface
    {
        return $this->fecha_egreso;
    }

    public function setFechaEgreso(?\DateTimeInterface $fecha_egreso): self
    {
        $this->fecha_egreso = $fecha_egreso;

        return $this;
    }

    public function getMotivoEgreso(): ?string
    {
        return $this->motivo_egreso;
    }

    public function setMotivoEgreso(string $motivo_egreso): self
    {
        $this->motivo_egreso = $motivo_egreso;

        return $this;
    }

    public function getFechaObito(): ?\DateTimeInterface
    {
        return $this->fecha_obito;
    }

    public function setFechaObito(?\DateTimeInterface $fecha_obito): self
    {
        $this->fecha_obito = $fecha_obito;

        return $this;
    }

    /**
     * @return Collection|InternacionCama[]
     */
    public function getInternacionCamas(): Collection
    {
        return $this->internacionCamas;
    }

    public function addInternacionCama(InternacionCama $internacionCama): self
    {
        if (!$this->internacionCamas->contains($internacionCama)) {
            $this->internacionCamas[] = $internacionCama;
            $internacionCama->setInternacionId($this);
        }

        return $this;
    }

    public function removeInternacionCama(InternacionCama $internacionCama): self
    {
        if ($this->internacionCamas->removeElement($internacionCama)) {
            // set the owning side to null (unless already changed)
            if ($internacionCama->getInternacionId() === $this) {
                $internacionCama->setInternacionId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Evolucion[]
     */
    public function getEvoluciones(): Collection
    {
        return $this->evoluciones;
    }

    public function addEvolucion(Evolucion $evolucion): self
    {
        if (!$this->evoluciones->contains($evolucion)) {
            $this->evoluciones[] = $evolucion;
            $evolucion->setInternacion($this);
        }

        return $this;
    }

    public function removeEvolucion(Evolucion $evolucion): self
    {
        if ($this->evoluciones->removeElement($evolucion)) {
            // set the owning side to null (unless already changed)
            if ($evolucion->getInternacion() === $this) {
                $evolucion->setInternacion(null);
            }
        }

        return $this;
    }
}
