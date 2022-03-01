<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\MaxDepth;
use App\Entity\Permiso;

/**
 * User
 *
 * @ORM\Table(name="`user`");
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository");
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=191, unique=true)
    */
    protected $email;

    /**
     * @ORM\Column(name="username", type="string", length=191, unique=true)
     */
    protected $username;

    protected $salt;

    /**
     * @ORM\Column(name="password", type="string", length=191)
     * @Serializer\Exclude()
     */
    protected $password;

    /**
     * @var string
     */
    protected $plainPassword;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="json_array")
     */
    protected $roles = [];

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=191, nullable=true)
     */
    private $legajo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo = 1;

    /**
     * @ORM\OneToMany(targetEntity=UserPaciente::class, mappedBy="user")
     */
    private $userPacientes;
    
    /**
     * @ORM\ManyToOne(targetEntity=Sistema::class, inversedBy="users", fetch="EAGER")
     * @MaxDepth(1)
     */
    private $sistema;

    /**
     * @ORM\OneToMany(targetEntity=Aviso::class, mappedBy="usuario")
     */
    private $avisos;

    public function __construct()
    {
        // $this->userPacientes = new ArrayCollection();
        $this->avisos = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        $this->password = null;
    }

    /**
     * Set roles
     *
     * @param array $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        //return ["ROLE_USER"];
        return $this->roles;
    }

    public function getSalt() {}

    public function eraseCredentials() {}

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     * @return self
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     * @return self
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $dateTimeNow = new DateTime('now');
        $this->setUpdatedAt($dateTimeNow);
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt($dateTimeNow);
        }
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(bool $activo): self
    {
        $this->activo = $activo;

        return $this;
    }

    // public function hasPermit(Permiso $permit): bool
    // {
    //     $ok = false;
    //     $rs = $this->getRoles();
    //     $i = 0;
    //     while(!$ok && sizeof($rs) > $i){
    //         $ok = $permit->hasRole($rs[$i]);
    //         $i++;
    //     }
    //     return $ok;
    // }

    // public function hasPermits(Array $permits): bool
    // {
    //     $ok = true;
    //     foreach($permits as $p){
    //         $ok = ($ok && $this->hasPermit($p));

    //     }

    //     return $ok;
    // }

    /**
    * @return Sistema
    */
    public function getSistema(): ?Sistema
    {
        return $this->sistema;
    }

    public function setSistema(Sistema $sistema): self
    {
        $this->sistema = $sistema;
        return $this;
    }

    // /**
    //  * @return Collection|UserPaciente[]
    //  */
    // public function getUserPacientes(): Collection
    // {
    //     return $this->userPacientes;
    // }

    // public function addUserPaciente(UserPaciente $userPaciente): self
    // {
    //     if (!$this->userPacientes->contains($userPaciente)) {
    //         $this->userPacientes[] = $userPaciente;
    //         $userPaciente->setUser($this);
    //     }

    //     return $this;
    // }

    // public function removeUserPaciente(UserPaciente $userPaciente): self
    // {
    //     if ($this->userPacientes->removeElement($userPaciente)) {
    //         // set the owning side to null (unless already changed)
    //         if ($userPaciente->getUser() === $this) {
    //             $userPaciente->setUser(null);
    //         }
    //     }

    //     return $this;
    // }

    /**
     * @return Collection|Aviso[]
     */
    public function getAvisos(): Collection
    {
        return $this->avisos;
    }

    public function addAviso(Aviso $aviso): self
    {
        if (!$this->avisos->contains($aviso)) {
            $this->avisos[] = $aviso;
            $aviso->setUsuario($this);
        }

        return $this;
    }

    public function removeAviso(Aviso $aviso): self
    {
        if ($this->avisos->removeElement($aviso)) {
            // set the owning side to null (unless already changed)
            if ($aviso->getUsuario() === $this) {
                $aviso->setUsuario(null);
            }
        }

        return $this;
    }


}
