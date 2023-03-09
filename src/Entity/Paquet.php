<?php

namespace App\Entity;

use App\Repository\PaquetRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PaquetRepository::class)]
class Paquet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("paquet")]
    #[Assert\NotBlank(message: "le champ est vide!!!")]
    private ?string $NomPacks = null;

    #[ORM\Column(length: 255)]
    #[Groups("paquet")]
    #[Assert\NotBlank(message: "le champ est vide!!!")]
    private ?string $Discribtion = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("paquet")]
    private ?\DateTimeInterface $DateDebutPacks = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("paquet")]
    private ?\DateTimeInterface $DateFinPacks = null;

    #[ORM\Column]
    #[Groups("paquet")]
    private ?bool $EtatPacks = null;

    #[ORM\Column(length: 255)]
    #[Groups("paquet")]
    #[Assert\NotBlank(message: "le champ est vide!!!")]
    private ?string $TypePacks = null;

    #[ORM\ManyToOne(inversedBy: 'Numcarte')]
    #[Groups("paquet")]
    private ?FidelityCard $Numcarte = null;

    #[ORM\Column(length: 255)]
    #[Groups("paquet")]
    private ?string $FileUpload = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPacks(): ?string
    {
        return $this->NomPacks;
    }

    public function setNomPacks(?string $NomPacks): self
    {
        $this->NomPacks = $NomPacks;

        return $this;
    }

    public function getDiscribtion(): ?string
    {
        return $this->Discribtion;
    }

    public function setDiscribtion(?string $Discribtion): self
    {
        $this->Discribtion = $Discribtion;

        return $this;
    }

    public function getDateDebutPacks(): ?\DateTimeInterface
    {
        return $this->DateDebutPacks;
    }

    public function setDateDebutPacks(?\DateTimeInterface $DateDebutPacks): self
    {
        $this->DateDebutPacks = $DateDebutPacks;

        return $this;
    }

    public function getDateFinPacks(): ?\DateTimeInterface
    {
        return $this->DateFinPacks;
    }

    public function setDateFinPacks(\DateTimeInterface $DateFinPacks): self
    {
        $this->DateFinPacks = $DateFinPacks;

        return $this;
    }

    public function isEtatPacks(): ?bool
    {
        return $this->EtatPacks;
    }

    public function setEtatPacks(?bool $EtatPacks): self
    {
        $this->EtatPacks = $EtatPacks;

        return $this;
    }

    public function getTypePacks(): ?string
    {
        return $this->TypePacks;
    }

    public function setTypePacks(?string $TypePacks): self
    {
        $this->TypePacks = $TypePacks;

        return $this;
    }

    public function getNumcarte(): ?FidelityCard
    {
        return $this->Numcarte;
    }

    public function setNumcarte(?FidelityCard $Numcarte): self
    {
        $this->Numcarte = $Numcarte;

        return $this;
    }

    public function getFileUpload(): ?string
    {
        return $this->FileUpload;
    }

    public function setFileUpload(?string $FileUpload): self
    {
        $this->FileUpload = $FileUpload;

        return $this;
    }
  
}
