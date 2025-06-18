<?php

namespace App\Entity;

use App\Repository\ReponsesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReponsesRepository::class)]
class Reponses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reponses')]
    private ?Invitations $invitation = null;

    #[ORM\Column(length: 180)]
    private ?string $commentaires = null;

    #[ORM\Column(length: 45)]
    private ?string $nom = null;

    #[ORM\Column(length: 45)]
    private ?string $prenom = null;

    #[ORM\Column]
    private ?bool $valider = null;

    #[ORM\Column]
    private ?\DateTime $dateReponse = null;

    /**
     * @var Collection<int, ReponsesCreneaux>
     */
    #[ORM\OneToMany(targetEntity: ReponsesCreneaux::class, mappedBy: 'reponse')]
    private Collection $reponsesCreneauxes;

    public function __construct()
    {
        $this->reponsesCreneauxes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInvitation(): ?Invitations
    {
        return $this->invitation;
    }

    public function setInvitation(?Invitations $invitation): static
    {
        $this->invitation = $invitation;

        return $this;
    }

    public function getCommentaires(): ?string
    {
        return $this->commentaires;
    }

    public function setCommentaires(string $commentaires): static
    {
        $this->commentaires = $commentaires;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function isValider(): ?bool
    {
        return $this->valider;
    }

    public function setValider(bool $valider): static
    {
        $this->valider = $valider;

        return $this;
    }

    public function getDateReponse(): ?\DateTime
    {
        return $this->dateReponse;
    }

    public function setDateReponse(\DateTime $dateReponse): static
    {
        $this->dateReponse = $dateReponse;

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
            $reponsesCreneaux->setReponse($this);
        }

        return $this;
    }

    public function removeReponsesCreneaux(ReponsesCreneaux $reponsesCreneaux): static
    {
        if ($this->reponsesCreneauxes->removeElement($reponsesCreneaux)) {
            // set the owning side to null (unless already changed)
            if ($reponsesCreneaux->getReponse() === $this) {
                $reponsesCreneaux->setReponse(null);
            }
        }

        return $this;
    }
}
