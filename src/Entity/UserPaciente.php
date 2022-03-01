<?php

namespace App\Entity;

use App\Repository\UserPacienteRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass=UserPacienteRepository::class)
 */
class UserPaciente
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userPacientes")
     * @ORM\JoinColumn(nullable=false)
     * @MaxDepth(1)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Paciente::class, inversedBy="userPacientes")
     * @ORM\JoinColumn(nullable=false)
     * @MaxDepth(1)
     */
    private $paciente;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_desde;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_hasta;

    public function __construct($paciente, $medico)
    {
       $this->fecha_desde = new \DateTime();
       $this->paciente = $paciente;
       $this->user = $medico;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
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
