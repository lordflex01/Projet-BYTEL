<?php

namespace App\Entity;

use App\Repository\TachesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass=TachesRepository::class)
 * @UniqueEntity(
 * fields={"libelle"},
 * errorPath="libelle",
 * message="Il semble que vous avez déjà crée cette tache."
 *)
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
     * @ORM\OneToMany(targetEntity=DateV::class, mappedBy="tache")
     */
    private $dateVs;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $domaine;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity=CodeProjet::class, inversedBy="tache")
     */
    private $codeProjet;



    public function __construct()
    {
        $this->dateVs = new ArrayCollection();
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

    public function __toString()
    {
        // to show the name of the Category in the select
        return $this->libelle;
        // to show the id of the Category in the select
        // return $this->id;
    }

    /**
     * @return Collection|DateV[]
     */
    public function getDateVs(): Collection
    {
        return $this->dateVs;
    }

    public function addDateV(DateV $dateV): self
    {
        if (!$this->dateVs->contains($dateV)) {
            $this->dateVs[] = $dateV;
            $dateV->setTache($this);
        }

        return $this;
    }

    public function removeDateV(DateV $dateV): self
    {
        if ($this->dateVs->removeElement($dateV)) {
            // set the owning side to null (unless already changed)
            if ($dateV->getTache() === $this) {
                $dateV->setTache(null);
            }
        }

        return $this;
    }

    public function getDomaine(): ?string
    {
        return $this->domaine;
    }

    public function setDomaine(?string $domaine): self
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function getCodeProjet(): ?CodeProjet
    {
        return $this->codeProjet;
    }

    public function setCodeProjet(?CodeProjet $codeProjet): self
    {
        $this->codeProjet = $codeProjet;

        return $this;
    }
}
