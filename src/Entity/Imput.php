<?php

namespace App\Entity;

use App\Repository\ImputRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImputRepository::class)
 */
class Imput
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity=Taches::class, inversedBy="user")
     */
    private $tache;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="imputs")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=DateV::class, mappedBy="imput", cascade={"persist"})
     */
    private $dateVs;

    public function __construct()
    {
        $this->dateVs = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTache(): ?Taches
    {
        return $this->tache;
    }

    public function setTache(?Taches $tache): self
    {
        $this->tache = $tache;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
            $dateV->setImput($this);
        }

        return $this;
    }

    public function removeDateV(DateV $dateV): self
    {
        if ($this->dateVs->removeElement($dateV)) {
            // set the owning side to null (unless already changed)
            if ($dateV->getImput() === $this) {
                $dateV->setImput(null);
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
