<?php

namespace App\Entity;

use App\Repository\DateVRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DateVRepository::class)
 */
class DateV
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $valeur;

    /**
     * @ORM\ManyToOne(targetEntity=Imput::class, inversedBy="dateVs")
     */
    private $imput;

    /**
     * @ORM\ManyToOne(targetEntity=Taches::class, inversedBy="dateVs")
     */
    private $tache;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getValeur(): ?float
    {
        return $this->valeur;
    }

    public function setValeur(?float $valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getImput(): ?Imput
    {
        return $this->imput;
    }

    public function setImput(?Imput $imput): self
    {
        $this->imput = $imput;

        return $this;
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
}
