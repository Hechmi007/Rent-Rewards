<?php

namespace App\Entity;

use App\Repository\FidelityCardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FidelityCardRepository::class)]
class FidelityCard
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups("card")]
    #[Assert\NotNull(message: "NOT NULL!!!")]
    #[Assert\NotBlank(message: "le champ est vide!!!")]
    private ?int $numcarte = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("paquet")]
    private ?\DateTimeInterface $datedebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("paquet")]
    private ?\DateTimeInterface $datefin = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $username = null;

    #[ORM\OneToMany(mappedBy: 'Numcarte', targetEntity: Paquet::class)]
    private Collection $Numcarte;

    public function __construct()
    {
        $this->Numcarte = new ArrayCollection();
    }


   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumcarte(): ?int
    {
        return $this->numcarte;
    }

    public function setNumcarte(?int $numcarte): self
    {
        $this->numcarte = $numcarte;

        return $this;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(?\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }

    public function getUsername(): ?User
    {
        return $this->username;
    }

    public function setUsername(?User $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function addNumcarte(Paquet $numcarte): self
    {
        if (!$this->Numcarte->contains($numcarte)) {
            $this->Numcarte->add($numcarte);
            $numcarte->setNumcarte($this);
        }

        return $this;
    }

    public function removeNumcarte(Paquet $numcarte): self
    {
        if ($this->Numcarte->removeElement($numcarte)) {
            // set the owning side to null (unless already changed)
            if ($numcarte->getNumcarte() === $this) {
                $numcarte->setNumcarte(null);
            }
        }

        return $this;
    }

   
}
