<?php

namespace App\Entity;

use App\Repository\PacienteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PacienteRepository::class)
 */
class Paciente
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $dni;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $apellido;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $direccion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telefono;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_nacimiento;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $obra_social;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $antecedentes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contacto_nombre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contacto_apellido;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contacto_telefono;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contacto_parentesco;

    /**
     * @ORM\OneToMany(targetEntity=Internacion::class, mappedBy="paciente")
     */
    private $internaciones;

    /**
     * @ORM\OneToMany(targetEntity=UserPaciente::class, mappedBy="paciente")
     */
    private $userPacientes;

    public function __construct($dni, 
                                $apellido, 
                                $nombre, 
                                $direccion, 
                                $telefono, 
                                $email, 
                                $fecha_nacimiento,
                                $obra_social,
                                $antecedentes,
                                $contacto_apellido,
                                $contacto_nombre,
                                $contacto_telefono,
                                $contacto_parentesco)

    {

        $this->dni = $dni;
        $this->apellido = $apellido;
        $this->nombre = $nombre;
        $this->direccion = $direccion;
        $this->telefono = $telefono;
        $this->email = $email;
        $this->fecha_nacimiento = $fecha_nacimiento;
        $this->obra_social = $obra_social;
        $this->antecedentes = $antecedentes;
        $this->contacto_nombre = $contacto_nombre;
        $this->contacto_apellido = $contacto_apellido;
        $this->contacto_telefono = $contacto_telefono;
        $this->contacto_parentesco = $contacto_parentesco;
        $this->internaciones = new ArrayCollection();
        $this->userPacientes = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDni(): ?int
    {
        return $this->dni;
    }

    public function setDni(int $dni): self
    {
        $this->dni = $dni;

        return $this;
    }

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): self
    {
        $this->apellido = $apellido;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFechaNacimiento(): ?\DateTimeInterface
    {
        return $this->fecha_nacimiento;
    }

    public function setFechaNacimiento(\DateTimeInterface $fecha_nacimiento): self
    {
        $this->fecha_nacimiento = $fecha_nacimiento;

        return $this;
    }

    public function getObraSocial(): ?string
    {
        return $this->obra_social;
    }

    public function setObraSocial(?string $obra_social): self
    {
        $this->obra_social = $obra_social;

        return $this;
    }

    public function getAntecedentes(): ?string
    {
        return $this->antecedentes;
    }

    public function setAntecedentes(?string $antecedentes): self
    {
        $this->antecedentes = $antecedentes;

        return $this;
    }

    public function getContactoNombre(): ?string
    {
        return $this->contacto_nombre;
    }

    public function setContactoNombre(?string $contacto_nombre): self
    {
        $this->contacto_nombre = $contacto_nombre;

        return $this;
    }

    public function getContactoApellido(): ?string
    {
        return $this->contacto_apellido;
    }

    public function setContactoApellido(?string $contacto_apellido): self
    {
        $this->contacto_apellido = $contacto_apellido;

        return $this;
    }

    public function getContactoTelefono(): ?string
    {
        return $this->contacto_telefono;
    }

    public function setContactoTelefono(?string $contacto_telefono): self
    {
        $this->contacto_telefono = $contacto_telefono;

        return $this;
    }

    public function getContactoParentesco(): ?string
    {
        return $this->contacto_parentesco;
    }

    public function setContactoParentesco(?string $contacto_parentesco): self
    {
        $this->contacto_parentesco = $contacto_parentesco;

        return $this;
    }

    /**
     * @return Collection|Internacion[]
     */
    public function getInternaciones(): Collection
    {
        return $this->internaciones;
    }

    public function addInternacion(Internacion $internacion): self
    {
        if (!$this->internaciones->contains($internacion)) {
            $this->internaciones[] = $internacion;
            $internacion->setPaciente($this);
        }

        return $this;
    }

    public function removeInternacion(Internacion $internacion): self
    {
        if ($this->internaciones->removeElement($internacion)) {
            // set the owning side to null (unless already changed)
            if ($internacion->getPaciente() === $this) {
                $internacion->setPaciente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserPaciente[]
     */
    public function getUserPacientes(): Collection
    {
        return $this->userPacientes;
    }

    public function addUserPaciente(UserPaciente $userPaciente): self
    {
        if (!$this->userPacientes->contains($userPaciente)) {
            $this->userPacientes[] = $userPaciente;
            $userPaciente->setPaciente($this);
        }

        return $this;
    }

    /**
     * @return Array|User[]
     */
    public function getUsers(): Array
    {
        $userPacientes = $this->getUserPacientes();
        $users = array();
        foreach($userPacientes as $up){$users[] = $up->getUser();}
        return $users;
    }

    public function removeUserPaciente(UserPaciente $userPaciente): self
    {
        if ($this->userPacientes->removeElement($userPaciente)) {
            // set the owning side to null (unless already changed)
            if ($userPaciente->getPaciente() === $this) {
                $userPaciente->setPaciente(null);
            }
        }

        return $this;
    }


}
