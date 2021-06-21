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

    /**
     * @ORM\OneToMany(targetEntity=DateV::class, mappedBy="codeprojet")
     */
    private $dateVs;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $budgetCLOE;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $chargeCLOE;

    /**
     * @ORM\OneToMany(targetEntity=Taches::class, mappedBy="codeProjet")
     */
    private $tache;

    public function __construct()
    {
        $this->imputations = new ArrayCollection();
        $this->dateVs = new ArrayCollection();
        $this->tache = new ArrayCollection();
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
            $dateV->setCodeprojet($this);
        }

        return $this;
    }

    public function removeDateV(DateV $dateV): self
    {
        if ($this->dateVs->removeElement($dateV)) {
            // set the owning side to null (unless already changed)
            if ($dateV->getCodeprojet() === $this) {
                $dateV->setCodeprojet(null);
            }
        }

        return $this;
    }

    public function getBudgetCLOE(): ?float
    {
        return $this->budgetCLOE;
    }

    public function setBudgetCLOE(?float $budgetCLOE): self
    {
        $this->budgetCLOE = $budgetCLOE;

        return $this;
    }

    public function getChargeCLOE(): ?float
    {
        return $this->chargeCLOE;
    }

    public function setChargeCLOE(?float $chargeCLOE): self
    {
        $this->chargeCLOE = $chargeCLOE;

        return $this;
    }

    /**
     * @return Collection|Taches[]
     */
    public function getTache(): Collection
    {
        return $this->tache;
    }

    public function addTache(Taches $tache): self
    {
        if (!$this->tache->contains($tache)) {
            $this->tache[] = $tache;
            $tache->setCodeProjet($this);
        }

        return $this;
    }

    public function removeTache(Taches $tache): self
    {
        if ($this->tache->removeElement($tache)) {
            // set the owning side to null (unless already changed)
            if ($tache->getCodeProjet() === $this) {
                $tache->setCodeProjet(null);
            }
        }

        return $this;
    }
}
