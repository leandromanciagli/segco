<?php

namespace App\Entity;

use App\Repository\SalaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SalaRepository::class)
 */
class Sala
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Sistema::class, inversedBy="salas")
     */
    private $sistema;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity=Cama::class, mappedBy="sala")
     */
    private $camas;

    public function __construct()
    {
        $this->camas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSistema(): ?Sistema
    {
        return $this->sistema;
    }

    public function setSistema(?Sistema $sistema): self
    {
        $this->sistema = $sistema;

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

    /**
     * @return Collection|Cama[]
     */
    public function getCamas(): Collection
    {
        return $this->camas;
    }

    public function addCama(Cama $cama): self
    {
        if (!$this->camas->contains($cama)) {
            $this->camas[] = $cama;
            $cama->setSalaId($this);
        }

        return $this;
    }

    public function removeCama(Cama $cama): self
    {
        if ($this->camas->removeElement($cama)) {
            // set the owning side to null (unless already changed)
            if ($cama->getSalaId() === $this) {
                $cama->setSalaId(null);
            }
        }

        return $this;
    }
}
