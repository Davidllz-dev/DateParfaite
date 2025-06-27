<?php

namespace App\Entity;

use App\Repository\ReponsesCreneauxRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReponsesCreneauxRepository::class)]
class ReponsesCreneaux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reponsesCreneauxes')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Creneaux $creneaux = null;

    #[ORM\ManyToOne(inversedBy: 'reponsesCreneauxes')]
    private ?Reponses $reponse = null;

    #[ORM\Column]
    private ?bool $confirmer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreneaux(): ?Creneaux
    {
        return $this->creneaux;
    }

    public function setCreneaux(?Creneaux $creneaux): static
    {
        $this->creneaux = $creneaux;
        return $this;
    }

    public function getReponse(): ?Reponses
    {
        return $this->reponse;
    }

    public function setReponse(?Reponses $reponse): static
    {
        $this->reponse = $reponse;
        return $this;
    }

    public function isConfirmer(): ?bool
    {
        return $this->confirmer;
    }

    public function setConfirmer(bool $confirmer): static
    {
        $this->confirmer = $confirmer;
        return $this;
    }
}
