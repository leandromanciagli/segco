<?php

namespace App\Entity;

use App\Repository\SistemaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\MaxDepth;


/**
 * @ORM\Entity(repositoryClass=SistemaRepository::class)
 */
class Sistema
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
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descrip;

    /**
     * @ORM\Column(type="integer")
     */
    private $camas_total;

    /**
     * @ORM\Column(type="integer")
     */
    private $camas_disponibles;

    /**
     * @ORM\Column(type="integer")
     */
    private $camas_ocupadas;

    /**
     * @var array
     *
     * @ORM\Column(name="sistemas_destino", type="json_array", nullable=true)
     */
    private $sistemas_destino = [];

    /**
     * @ORM\OneToMany(targetEntity=Sala::class, mappedBy="sistema")
     * @MaxDepth(1)
     */
    private $salas;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="sistema")
     * @MaxDepth(1)
     */
    private $users;

    public function __construct()
    {
        $this->salas = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescrip(): ?string
    {
        return $this->descrip;
    }

    public function setDescrip(?string $descrip): self
    {
        $this->descrip = $descrip;

        return $this;
    }

    public function getCamasTotal(): ?int
    {
        return $this->camas_total;
    }

    public function setCamasTotal(int $camas_total): self
    {
        $this->camas_total = $camas_total;

        return $this;
    }

    public function getCamasDisponibles(): ?int
    {
        return $this->camas_disponibles;
    }

    public function setCamasDisponibles(int $camas_disponibles): self
    {
        $this->camas_disponibles = $camas_disponibles;

        return $this;
    }

    public function getCamasOcupadas(): ?int
    {
        return $this->camas_ocupadas;
    }

    public function setCamasOcupadas(int $camas_ocupadas): self
    {
        $this->camas_ocupadas = $camas_ocupadas;

        return $this;
    }

    /**
     * Get sistemas destino
     *
     * @return array
     */
    public function getSistemasDestino()
    {
        return $this->sistemas_destino;
    }

    /**
     * @return Collection|Sala[]
     */
    public function getSalas(): Collection
    {
        return $this->salas;
    }

    public function addSala(Sala $sala): self
    {
        if (!$this->salas->contains($sala)) {
            $this->salas[] = $sala;
            $sala->setSistema($this);
        }

        return $this;
    }

    public function removeSala(Sala $sala): self
    {
        if ($this->salas->removeElement($sala)) {
            // set the owning side to null (unless already changed)
            if ($sala->getSistema() === $this) {
                $sala->setSistema(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setSistema($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getSistema() === $this) {
                $user->setSistema(null);
            }
        }

        return $this;
    }

}
