<?php

namespace App\Entity;

use App\Repository\ImputRepository;
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
     * @ORM\Column(type="float", nullable=true)
     */
    private $valeur;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

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

    public function getValeur(): ?float
    {
        return $this->valeur;
    }

    public function setValeur(?float $valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
