<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjetRepository::class)
 */
class Projet
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
     * @ORM\Column(type="boolean")
     */
    private $statut;

    /**
     * @ORM\OneToMany(targetEntity=CodeProjet::class, mappedBy="projet")
     */
    private $codeprojet;

    public function __construct()
    {
        $this->codeprojet = new ArrayCollection();
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

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection|CodeProjet[]
     */
    public function getCodeprojet(): Collection
    {
        return $this->codeprojet;
    }

    public function addCodeprojet(CodeProjet $codeprojet): self
    {
        if (!$this->codeprojet->contains($codeprojet)) {
            $this->codeprojet[] = $codeprojet;
            $codeprojet->setProjet($this);
        }

        return $this;
    }

    public function removeCodeprojet(CodeProjet $codeprojet): self
    {
        if ($this->codeprojet->removeElement($codeprojet)) {
            // set the owning side to null (unless already changed)
            if ($codeprojet->getProjet() === $this) {
                $codeprojet->setProjet(null);
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
