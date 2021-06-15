<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 *  @UniqueEntity(
 * fields={"email"},
 * errorPath="email",
 * message="Il semble que vous avez déjà inscrit cet e-mail."
 *)
 */

class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *  message="Votre e-mail n'est pas valide"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */

    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     */
    private $username;

    /**
     * @ORM\OneToOne(targetEntity=Image::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $poste;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $site;

    /**
     * @ORM\OneToMany(targetEntity=Imputation::class, mappedBy="user")
     */
    private $imputations;

    /**
     * @ORM\ManyToOne(targetEntity=Projet::class, inversedBy="users")
     */
    private $projet;

    /**
     * @ORM\OneToMany(targetEntity=Imput::class, mappedBy="user")
     */
    private $imputs;

    public function __construct()
    {
        $this->imputations = new ArrayCollection();
        $this->imputs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(Image $image): self
    {
        // set the owning side of the relation if necessary
        if ($image->getUser() !== $this) {
            $image->setUser($this);
        }

        $this->image = $image;

        return $this;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(string $poste): self
    {
        $this->poste = $poste;

        return $this;
    }


    public function getSite(): ?string
    {
        return $this->site;
    }

    public function setSite(?string $site): self
    {
        $this->site = $site;

        return $this;
    }

    /**
     * @return Collection|Imputation[]
     */
    public function getImputations(): Collection
    {
        return $this->imputations;
    }

    public function addImputation(Imputation $imputation): self
    {
        if (!$this->imputations->contains($imputation)) {
            $this->imputations[] = $imputation;
            $imputation->setUser($this);
        }

        return $this;
    }

    public function removeImputation(Imputation $imputation): self
    {
        if ($this->imputations->removeElement($imputation)) {
            // set the owning side to null (unless already changed)
            if ($imputation->getUser() === $this) {
                $imputation->setUser(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        // to show the name of the Category in the select
        return $this->username;
        // to show the id of the Category in the select
        // return $this->id;
    }

    public function getProjet(): ?projet
    {
        return $this->projet;
    }

    public function setProjet(?projet $projet): self
    {
        $this->projet = $projet;

        return $this;
    }

    /**
     * @return Collection|Imput[]
     */
    public function getImputs(): Collection
    {
        return $this->imputs;
    }

    public function addImput(Imput $imput): self
    {
        if (!$this->imputs->contains($imput)) {
            $this->imputs[] = $imput;
            $imput->setUser($this);
        }

        return $this;
    }

    public function removeImput(Imput $imput): self
    {
        if ($this->imputs->removeElement($imput)) {
            // set the owning side to null (unless already changed)
            if ($imput->getUser() === $this) {
                $imput->setUser(null);
            }
        }

        return $this;
    }
}
