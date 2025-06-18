<?php

namespace App\Entity;

use App\Repository\CreneauxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CreneauxRepository::class)]
class Creneaux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'creneaux')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Reunions $reunion = null;

    #[ORM\Column]
    private ?\DateTimeInterface $start_time = null;

    #[ORM\Column]
    private ?\DateTimeInterface $end_time = null;

    /**
     * @var Collection<int, ReponsesCreneaux>
     */
    #[ORM\OneToMany(targetEntity: ReponsesCreneaux::class, mappedBy: 'creneaux')]
    private Collection $reponsesCreneauxes;

    public function __construct()
    {
        $this->reponsesCreneauxes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReunion(): ?Reunions
    {
        return $this->reunion;
    }

    public function setReunion(?Reunions $reunion): static
    {
        $this->reunion = $reunion;
        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->start_time;
    }

    public function setStartTime(\DateTimeInterface $start_time): static
    {
        $this->start_time = $start_time;
        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->end_time;
    }

    public function setEndTime(\DateTimeInterface $end_time): static
    {
        $this->end_time = $end_time;
        return $this;
    }

    /**
     * @return Collection<int, ReponsesCreneaux>
     */
    public function getReponsesCreneauxes(): Collection
    {
        return $this->reponsesCreneauxes;
    }

    public function addReponsesCreneaux(ReponsesCreneaux $reponsesCreneaux): static
    {
        if (!$this->reponsesCreneauxes->contains($reponsesCreneaux)) {
            $this->reponsesCreneauxes->add($reponsesCreneaux);
            $reponsesCreneaux->setCreneaux($this);
        }

        return $this;
    }

    public function removeReponsesCreneaux(ReponsesCreneaux $reponsesCreneaux): static
    {
        if ($this->reponsesCreneauxes->removeElement($reponsesCreneaux)) {
            // set the owning side to null (unless already changed)
            if ($reponsesCreneaux->getCreneaux() === $this) {
                $reponsesCreneaux->setCreneaux(null);
            }
        }

        return $this;
    }
}
