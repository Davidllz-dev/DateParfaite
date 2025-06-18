<?php

namespace App\Entity;

use App\Repository\InvitationsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvitationsRepository::class)]
class Invitations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'invitations')]
    private ?Reunions $reunion = null;

    #[ORM\Column(length: 50)]
    private ?string $invite_email = null;

    #[ORM\Column(length: 100)]
    private ?string $token = null;

    /**
     * @var Collection<int, Reponses>
     */
    #[ORM\OneToMany(targetEntity: Reponses::class, mappedBy: 'invitation')]
    private Collection $reponses;

    public function __construct()
    {
        $this->reponses = new ArrayCollection();
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

    public function getInviteEmail(): ?string
    {
        return $this->invite_email;
    }

    public function setInviteEmail(string $invite_email): static
    {
        $this->invite_email = $invite_email;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return Collection<int, Reponses>
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponses $reponse): static
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses->add($reponse);
            $reponse->setInvitation($this);
        }

        return $this;
    }

    public function removeReponse(Reponses $reponse): static
    {
        if ($this->reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getInvitation() === $this) {
                $reponse->setInvitation(null);
            }
        }

        return $this;
    }
}
