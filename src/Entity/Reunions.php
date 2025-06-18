<?php

namespace App\Entity;

use App\Enum\ReunionStatus;
use App\Repository\ReunionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReunionsRepository::class)]
class Reunions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reunions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user = null;

    #[ORM\Column(length: 45)]
    private ?string $titre = null;

    #[ORM\Column(length: 45)]
    private ?string $description = null;

    #[ORM\Column(length: 45)]
    private ?string $lieu = null;

    #[ORM\Column(type: 'string', enumType: ReunionStatus::class)]
    private ?ReunionStatus $status = null;

    #[ORM\Column(name: "dateCreation", type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $dateCreation = null;

    /**
     * @var Collection<int, Creneaux>
     */
    #[ORM\OneToMany(mappedBy: 'reunion', targetEntity: Creneaux::class, cascade: ['persist'], orphanRemoval: true)]
private Collection $creneaux;


    /**
     * @var Collection<int, Invitations>
     */
    #[ORM\OneToMany(targetEntity: Invitations::class, mappedBy: 'reunion')]
    private Collection $invitations;

    public function __construct()
    {
        $this->creneaux = new ArrayCollection();
        $this->invitations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;
        return $this;
    }

    public function getStatus(): ?ReunionStatus
    {
        return $this->status;
    }

    public function setStatus(ReunionStatus $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    /**
     * @return Collection<int, Creneaux>
     */
    public function getCreneaux(): Collection
    {
        return $this->creneaux;
    }

    public function addCreneau(Creneaux $creneau): static
    {
        if (!$this->creneaux->contains($creneau)) {
            $this->creneaux->add($creneau);
            $creneau->setReunion($this);
        }

        return $this;
    }

    public function removeCreneau(Creneaux $creneau): static
    {
        if ($this->creneaux->removeElement($creneau)) {
            if ($creneau->getReunion() === $this) {
                $creneau->setReunion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Invitations>
     */
    public function getInvitations(): Collection
    {
        return $this->invitations;
    }

    public function addInvitation(Invitations $invitation): static
    {
        if (!$this->invitations->contains($invitation)) {
            $this->invitations->add($invitation);
            $invitation->setReunion($this);
        }

        return $this;
    }

    public function removeInvitation(Invitations $invitation): static
    {
        if ($this->invitations->removeElement($invitation)) {
            // set the owning side to null (unless already changed)
            if ($invitation->getReunion() === $this) {
                $invitation->setReunion(null);
            }
        }

        return $this;
    }
}
