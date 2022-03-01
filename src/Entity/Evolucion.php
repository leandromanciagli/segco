<?php

namespace App\Entity;

use App\Repository\EvolucionRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\MaxDepth;


/**
 * @ORM\Entity(repositoryClass=EvolucionRepository::class)
 */
class Evolucion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Internacion::class, inversedBy="evoluciones")
     * @ORM\JoinColumn(nullable=false)
     * @MaxDepth(1)
     */
    private $internacion;

    /**
     * 
     * @ORM\Column(type="integer")
     */
    private $sistema_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_carga;

    /**
     * @ORM\Column(type="float")
     */
    private $temperatura;

    /**
     * @ORM\Column(type="integer")
     */
    private $tension_arterial_sistolica;

    /**
     * @ORM\Column(type="integer")
     */
    private $tension_arterial_diastolica;

    /**
     * @ORM\Column(type="integer")
     */
    private $frecuencia_cardiaca;

    /**
     * @ORM\Column(type="integer")
     */
    private $frecuencia_respiratoria;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mecanica_ventilatoria;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $canula_nasal_oxigeno;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $mascara_con_reservorio;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $saturacion_oxigeno;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pafi;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $prono_vigil;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $tos;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $disnea;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $estabilidad_desaparicion_sintomas_resp;

    /**
     * @ORM\Column(type="boolean")
     */
    private $somnolencia;

    /**
     * @ORM\Column(type="boolean")
     */
    private $anosmia;

    /**
     * @ORM\Column(type="boolean")
     */
    private $disgeusia;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $radiografia_tipo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $radiografia_descrip;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tomografia_torax_tipo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tomografia_torax_descrip;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $electrocardiograma_tipo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $electrocardiograma_descrip;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pcr_covid_tipo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pcr_covid_descrip;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $observacion;

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

    public function getSistema(): ?int
    {
        return $this->sistema_id;
    }

    public function setSistema(?int $sistema_id): self
    {
        $this->sistema_id = $sistema_id;

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

    public function getTemperatura(): ?float
    {
        return $this->temperatura;
    }

    public function setTemperatura(float $temperatura): self
    {
        $this->temperatura = $temperatura;

        return $this;
    }

    public function getTensionArterialSistolica(): ?int
    {
        return $this->tension_arterial_sistolica;
    }

    public function setTensionArterialSistolica(int $tension_arterial_sistolica): self
    {
        $this->tension_arterial_sistolica = $tension_arterial_sistolica;

        return $this;
    }

    public function getTensionArterialDiastolica(): ?int
    {
        return $this->tension_arterial_diastolica;
    }

    public function setTensionArterialDiastolica(int $tension_arterial_diastolica): self
    {
        $this->tension_arterial_diastolica = $tension_arterial_diastolica;

        return $this;
    }

    public function getFrecuenciaCardiaca(): ?int
    {
        return $this->frecuencia_cardiaca;
    }

    public function setFrecuenciaCardiaca(int $frecuencia_cardiaca): self
    {
        $this->frecuencia_cardiaca = $frecuencia_cardiaca;

        return $this;
    }

    public function getFrecuenciaRespiratoria(): ?int
    {
        return $this->frecuencia_respiratoria;
    }

    public function setFrecuenciaRespiratoria(int $frecuencia_respiratoria): self
    {
        $this->frecuencia_respiratoria = $frecuencia_respiratoria;

        return $this;
    }

    public function getMecanicaVentilatoria(): ?string
    {
        return $this->mecanica_ventilatoria;
    }

    public function setMecanicaVentilatoria(string $mecanica_ventilatoria): self
    {
        $this->mecanica_ventilatoria = $mecanica_ventilatoria;

        return $this;
    }

    public function getCanulaNasalOxigeno(): ?float
    {
        return $this->canula_nasal_oxigeno;
    }

    public function setCanulaNasalOxigeno(?float $canula_nasal_oxigeno): self
    {
        $this->canula_nasal_oxigeno = $canula_nasal_oxigeno;

        return $this;
    }

    public function getMascaraConReservorio(): ?float
    {
        return $this->mascara_con_reservorio;
    }

    public function setMascaraConReservorio(?float $mascara_con_reservorio): self
    {
        $this->mascara_con_reservorio = $mascara_con_reservorio;

        return $this;
    }

    public function getSaturacionOxigeno(): ?int
    {
        return $this->saturacion_oxigeno;
    }

    public function setSaturacionOxigeno(?int $saturacion_oxigeno): self
    {
        $this->saturacion_oxigeno = $saturacion_oxigeno;

        return $this;
    }

    public function getPafi(): ?int
    {
        return $this->pafi;
    }

    public function setPafi(?int $pafi): self
    {
        $this->pafi = $pafi;

        return $this;
    }

    public function getPronoVigil(): ?bool
    {
        return $this->prono_vigil;
    }

    public function setPronoVigil(bool $prono_vigil): self
    {
        $this->prono_vigil = $prono_vigil;

        return $this;
    }

    public function getTos(): ?bool
    {
        return $this->tos;
    }

    public function setTos(bool $tos): self
    {
        $this->tos = $tos;

        return $this;
    }

    public function getDisnea(): ?int
    {
        return $this->disnea;
    }

    public function setDisnea(?int $disnea): self
    {
        $this->disnea = $disnea;

        return $this;
    }

    public function getEstabilidadDesaparicionSintomasResp(): ?bool
    {
        return $this->estabilidad_desaparicion_sintomas_resp;
    }

    public function setEstabilidadDesaparicionSintomasResp(?bool $estabilidad_desaparicion_sintomas_resp): self
    {
        $this->estabilidad_desaparicion_sintomas_resp = $estabilidad_desaparicion_sintomas_resp;

        return $this;
    }

    public function getSomnolencia(): ?bool
    {
        return $this->somnolencia;
    }

    public function setSomnolencia(bool $somnolencia): self
    {
        $this->somnolencia = $somnolencia;

        return $this;
    }

    public function getAnosmia(): ?bool
    {
        return $this->anosmia;
    }

    public function setAnosmia(bool $anosmia): self
    {
        $this->anosmia = $anosmia;

        return $this;
    }

    public function getDisgeusia(): ?bool
    {
        return $this->disgeusia;
    }

    public function setDisgeusia(bool $disgeusia): self
    {
        $this->disgeusia = $disgeusia;

        return $this;
    }

    public function getRadiografiaTipo(): ?string
    {
        return $this->radiografia_tipo;
    }

    public function setRadiografiaTipo(?string $radiografia_tipo): self
    {
        $this->radiografia_tipo = $radiografia_tipo;

        return $this;
    }

    public function getRadiografiaDescrip(): ?string
    {
        return $this->radiografia_descrip;
    }

    public function setRadiografiaDescrip(?string $radiografia_descrip): self
    {
        $this->radiografia_descrip = $radiografia_descrip;

        return $this;
    }

    public function getTomografiaToraxTipo(): ?string
    {
        return $this->tomografia_torax_tipo;
    }

    public function setTomografiaToraxTipo(?string $tomografia_torax_tipo): self
    {
        $this->tomografia_torax_tipo = $tomografia_torax_tipo;

        return $this;
    }

    public function getTomografiaToraxDescrip(): ?string
    {
        return $this->tomografia_torax_descrip;
    }

    public function setTomografiaToraxDescrip(?string $tomografia_torax_descrip): self
    {
        $this->tomografia_torax_descrip = $tomografia_torax_descrip;

        return $this;
    }

    public function getElectrocardiogramaTipo(): ?string
    {
        return $this->electrocardiograma_tipo;
    }

    public function setElectrocardiogramaTipo(?string $electrocardiograma_tipo): self
    {
        $this->electrocardiograma_tipo = $electrocardiograma_tipo;

        return $this;
    }

    public function getElectrocardiogramaDescrip(): ?string
    {
        return $this->electrocardiograma_descrip;
    }

    public function setElectrocardiogramaDescrip(?string $electrocardiograma_descrip): self
    {
        $this->electrocardiograma_descrip = $electrocardiograma_descrip;

        return $this;
    }

    public function getPcrCovidTipo(): ?string
    {
        return $this->pcr_covid_tipo;
    }

    public function setPcrCovidTipo(?string $pcr_covid_tipo): self
    {
        $this->pcr_covid_tipo = $pcr_covid_tipo;

        return $this;
    }

    public function getPcrCovidDescrip(): ?string
    {
        return $this->pcr_covid_descrip;
    }

    public function setPcrCovidDescrip(?string $pcr_covid_descrip): self
    {
        $this->pcr_covid_descrip = $pcr_covid_descrip;

        return $this;
    }

    public function getObservacion(): ?string
    {
        return $this->observacion;
    }

    public function setObservacion(?string $observacion): self
    {
        $this->observacion = $observacion;

        return $this;
    }
}
