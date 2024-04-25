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
    private $chageJH;

    /**
     * @ORM\OneToMany(targetEntity=DateV::class, mappedBy="codeprojet")
     */
    private $dateVs;

    /**
     * @ORM\OneToMany(targetEntity=Taches::class, mappedBy="codeProjet")
     */
    private $tache;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $budgetConsomme;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $chargeConsomme;

    /**
     * @ORM\OneToMany(targetEntity=GestionProjet::class, mappedBy="codeProjet")
     */
    private $gestion;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $budgetNRJ;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $budgetCLOE;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $budgetTransverse;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $budgetDECO;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $chargeCLOE;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $chargeDECO;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $chargeNRJ;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $chargeTransverse;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $budgetNRJConsomme;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $budgetDECOConsomme;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $budgetCLOEConsomme;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $budgetTransverseconsomme;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $chargeNRJConsomme;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $chargeDECOConsomme;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $chargeCLOEConsomme;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $chargeTransverseconsomme;



    public function __construct()
    {
        $this->imputations = new ArrayCollection();
        $this->dateVs = new ArrayCollection();
        $this->tache = new ArrayCollection();
        $this->gestion = new ArrayCollection();
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

    public function getChageJH(): ?float
    {
        return $this->chageJH;
    }

    public function setChageJH(?float $chageJH): self
    {
        $this->chageJH = $chageJH;

        return $this;
    }

    public function getBudgetConsomme(): ?float
    {
        return $this->budgetConsomme;
    }

    public function setBudgetConsomme(?float $budgetConsomme): self
    {
        $this->budgetConsomme = $budgetConsomme;

        return $this;
    }

    public function getChargeConsomme(): ?float
    {
        return $this->chargeConsomme;
    }

    public function setChargeConsomme(?float $chargeConsomme): self
    {
        $this->chargeConsomme = $chargeConsomme;

        return $this;
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

    public function getBudgetCLOE(): ?float
    {
        return $this->budgetCLOE;
    }

    public function setBudgetCLOE(?float $budgetCLOE): self
    {
        $this->budgetCLOE = $budgetCLOE;

        return $this;
    }

    public function getBudgetTransverse(): ?float
    {
        return $this->budgetCLOE;
    }

    public function setBudgetTransverse(?float $budgetCLOE): self
    {
        $this->budgetCLOE = $budgetCLOE;

        return $this;
    }

    public function getChargeNRJ(): ?float
    {
        return $this->chargeNRJ;
    }

    public function setChargeNRJ(?float $chargeNRJ): self
    {
        $this->chargeNRJ = $chargeNRJ;
        return $this;
    }

    public function getChargeDECO(): ?float
    {
        return $this->chargeDECO;
    }

    public function setChargeDECO(?float $chargeDECO): self
    {
        $this->chargeDECO = $chargeDECO;
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

    public function getChargeTransverse(): ?float
    {
        return $this->chargeTransverse;
    }

    public function setChargeTransverse(?float $chargeTransverse): self
    {
        $this->chargeTransverse = $chargeTransverse;
        return $this;
    }

    public function getBudgetNRJConsomme(): ?float
    {
        return $this->budgetNRJConsomme;
    }

    public function setBudgetNRJConsomme(?float $budgetNRJConsomme): self
    {
        $this->budgetNRJConsomme = $budgetNRJConsomme;
        return $this;
    }

    public function getBudgetDECOConsomme(): ?float
    {
        return $this->budgetDECOConsomme;
    }

    public function setBudgetDECOConsomme(?float $budgetDECOConsomme): self
    {
        $this->budgetDECOConsomme = $budgetDECOConsomme;
        return $this;
    }

    public function getBudgetCLOEConsomme(): ?float
    {
        return $this->budgetCLOEConsomme;
    }

    public function setBudgetCLOEConsomme(?float $budgetCLOEConsomme): self
    {
        $this->budgetCLOEConsomme = $budgetCLOEConsomme;
        return $this;
    }

    public function getbudgetTransverseconsomme(): ?float
    {
        return $this->budgetTransverseconsomme;
    }

    public function setbudgetTransverseconsomme(?float $budgetTransverseconsomme): self
    {
        $this->budgetTransverseconsomme = $budgetTransverseconsomme;
        return $this;
    }

    public function getChargeNRJConsomme(): ?float
    {
        return $this->chargeNRJConsomme;
    }

    public function setChargeNRJConsomme(?float $chargeNRJConsomme): self
    {
        $this->chargeNRJConsomme = $chargeNRJConsomme;
        return $this;
    }

    public function getChargeDECOConsomme(): ?float
    {
        return $this->chargeDECOConsomme;
    }

    public function setChargeDECOConsomme(?float $chargeDECOConsomme): self
    {
        $this->chargeDECOConsomme = $chargeDECOConsomme;
        return $this;
    }

    public function getChargeCLOEConsomme(): ?float
    {
        return $this->chargeCLOEConsomme;
    }

    public function setChargeCLOEConsomme(?float $chargeCLOEConsomme): self
    {
        $this->chargeCLOEConsomme = $chargeCLOEConsomme;
        return $this;
    }

    public function getchargeTransverseconsomme(): ?float
    {
        return $this->chargeTransverseconsomme;
    }

    public function setchargeTransverseconsomme(?float $chargeTransverseconsomme): self
    {
        $this->chargeTransverseconsomme = $chargeTransverseconsomme;
        return $this;
    }


    /**
     * @return Collection|GestionProjet[]
     */
    public function getGestion(): Collection
    {
        return $this->gestion;
    }
    public function addGestion(GestionProjet $gest): self
    {
        if (!$this->gestion->contains($gest)) {
            $this->gestion[] = $gest;
            $gest->setCodeProjet($this);
        }
        return $this;
    }
    public function removeGestion(GestionProjet $gest): self
    {
        if ($this->gestion->removeElement($gest)) {
            // set the owning side to null (unless already changed)
            if ($gest->getCodeProjet() === $this) {
                $gest->setCodeProjet(null);
            }
        }
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
}
