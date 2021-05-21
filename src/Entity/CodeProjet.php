<?php

namespace App\Entity;

use App\Repository\CodeProjetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CodeProjetRepository::class)
 * @UniqueEntity(
 * fields={"libelle"},
 * errorPath="libelle",
 * message="Il semble que vous avez déjà crée ce code projet."
 *)
 */
class CodeProjet
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
     * @ORM\Column(type="float", nullable=true)
     */
    private $budget;


    /**
     * @ORM\Column(type="date")
     */
    private $dateD;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $DateF;

    /**
     * @ORM\OneToMany(targetEntity=Taches::class, mappedBy="codeprojet", orphanRemoval=true)
     */
    private $taches;

    /**
     * @ORM\OneToMany(targetEntity=Imputation::class, mappedBy="codeprojet")
     */
    private $imputations;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $budgetNRJ;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $budgetDECO;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $chageJH;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $chageNRJ;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $chageDECO;

    public function __construct()
    {
        $this->taches = new ArrayCollection();
        $this->imputations = new ArrayCollection();
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

    public function getBudget(): ?float
    {
        return $this->budget;
    }

    public function setBudget(?float $budget): self
    {
        $this->budget = $budget;

        return $this;
    }



    public function getDateD(): ?\DateTimeInterface
    {
        return $this->dateD;
    }

    public function setDateD(\DateTimeInterface $dateD): self
    {
        $this->dateD = $dateD;

        return $this;
    }

    public function getDateF(): ?\DateTimeInterface
    {
        return $this->DateF;
    }

    public function setDateF(?\DateTimeInterface $DateF): self
    {
        $this->DateF = $DateF;

        return $this;
    }

    /**
     * @return Collection|Taches[]
     */
    public function getTaches(): Collection
    {
        return $this->taches;
    }

    public function addTach(Taches $tach): self
    {
        if (!$this->taches->contains($tach)) {
            $this->taches[] = $tach;
            $tach->setCodeprojet($this);
        }

        return $this;
    }

    public function removeTach(Taches $tach): self
    {
        if ($this->taches->removeElement($tach)) {
            // set the owning side to null (unless already changed)
            if ($tach->getCodeprojet() === $this) {
                $tach->setCodeprojet(null);
            }
        }

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
            $imputation->setCodeprojet($this);
        }

        return $this;
    }

    public function removeImputation(Imputation $imputation): self
    {
        if ($this->imputations->removeElement($imputation)) {
            // set the owning side to null (unless already changed)
            if ($imputation->getCodeprojet() === $this) {
                $imputation->setCodeprojet(null);
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

    public function getBudgetNRJ(): ?float
    {
        return $this->budgetNRJ;
    }

    public function setBudgetNRJ(?float $budgetNRJ): self
    {
        $this->budgetNRJ = $budgetNRJ;

        return $this;
    }

    public function getBudgetDECO(): ?float
    {
        return $this->budgetDECO;
    }

    public function setBudgetDECO(?float $budgetDECO): self
    {
        $this->budgetDECO = $budgetDECO;

        return $this;
    }

    public function getChageJH(): ?float
    {
        return $this->chageJH;
    }

    public function setChageJH(?float $chageJH): self
    {
        $this->chageJH = $chageJH;

        return $this;
    }

    public function getChageNRJ(): ?float
    {
        return $this->chageNRJ;
    }

    public function setChageNRJ(?float $chageNRJ): self
    {
        $this->chageNRJ = $chageNRJ;

        return $this;
    }

    public function getChageDECO(): ?float
    {
        return $this->chageDECO;
    }

    public function setChageDECO(?float $chageDECO): self
    {
        $this->chageDECO = $chageDECO;

        return $this;
    }
}
