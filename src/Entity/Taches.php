<?php

namespace App\Entity;

use App\Repository\TachesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TachesRepository::class)
 */
class Taches
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
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=CodeProjet::class, inversedBy="taches")
     * @ORM\JoinColumn(nullable=false)
     */
    private $codeprojet;

    /**
     * @ORM\OneToMany(targetEntity=Imput::class, mappedBy="tache")
     */
    private $imput;

    public function __construct()
    {
        $this->imput = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCodeprojet(): ?CodeProjet
    {
        return $this->codeprojet;
    }

    public function setCodeprojet(?CodeProjet $codeprojet): self
    {
        $this->codeprojet = $codeprojet;

        return $this;
    }

    /**
     * @return Collection|Imput[]
     */
    public function getImput(): Collection
    {
        return $this->imput;
    }

    public function addImput(Imput $imput): self
    {
        if (!$this->imput->contains($imput)) {
            $this->imput[] = $imput;
            $imput->setTache($this);
        }

        return $this;
    }

    public function removeImput(Imput $imput): self
    {
        if ($this->imput->removeElement($imput)) {
            // set the owning side to null (unless already changed)
            if ($imput->getTache() === $this) {
                $imput->setTache(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        // to show the name of the Category in the select
        return $this->libelle;
        // to show the id of the Category in the select
        // return $this->id;
    }
}
