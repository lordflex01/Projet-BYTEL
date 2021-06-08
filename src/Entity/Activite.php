<?php

namespace App\Entity;

use App\Repository\ActiviteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActiviteRepository::class)
 */
class Activite
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
     * @ORM\OneToMany(targetEntity=DateV::class, mappedBy="activite")
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
            $dateV->setActivite($this);
        }

        return $this;
    }

    public function removeDateV(DateV $dateV): self
    {
        if ($this->dateVs->removeElement($dateV)) {
            // set the owning side to null (unless already changed)
            if ($dateV->getActivite() === $this) {
                $dateV->setActivite(null);
            }
        }

        return $this;
    }
}
