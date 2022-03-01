<?php

namespace App\Entity;

use App\Repository\CamaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CamaRepository::class)
 */
class Cama
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Sala::class, inversedBy="camas")
     */
    private $sala;

    /**
     * @ORM\Column(type="integer")
     */
    private $numero;

    /**
     * @ORM\Column(type="string")
     */
    private $estado;

    /**
     * @ORM\OneToMany(targetEntity=InternacionCama::class, mappedBy="cama")
     */
    private $internacionCamas;

    public function __construct()
    {
        $this->internacionCamas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSala(): ?Sala
    {
        return $this->sala;
    }

    public function setSala(?Sala $sala): self
    {
        $this->sala = $sala;

        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

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
            $internacionCama->setCamaId($this);
        }

        return $this;
    }

    public function removeInternacionCama(InternacionCama $internacionCama): self
    {
        if ($this->internacionCamas->removeElement($internacionCama)) {
            // set the owning side to null (unless already changed)
            if ($internacionCama->getCamaId() === $this) {
                $internacionCama->setCamaId(null);
            }
        }

        return $this;
    }
}
