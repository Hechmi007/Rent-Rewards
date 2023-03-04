<?php

namespace App\Entity;

use App\Repository\DemandeCreditRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandeCreditRepository::class)]
class DemandeCredit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateRemboursement = null;

    #[ORM\Column]
    private ?float $Montant = null;

    #[ORM\Column]
    private ?bool $Etat = null;

    #[ORM\Column(length: 255)]
    private ?string $DurationCredit = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $username = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Wallet $points = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRemboursement(): ?\DateTimeInterface
    {
        return $this->DateRemboursement;
    }

    public function setDateRemboursement(\DateTimeInterface $DateRemboursement): self
    {
        $this->DateRemboursement = $DateRemboursement;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->Montant;
    }

    public function setMontant(float $Montant): self
    {
        $this->Montant = $Montant;

        return $this;
    }

    public function isEtat(): ?bool
    {
        return $this->Etat;
    }

    public function setEtat(bool $Etat): self
    {
        $this->Etat = $Etat;

        return $this;
    }

    public function getDurationCredit(): ?string
    {
        return $this->DurationCredit;
    }

    public function setDurationCredit(string $DurationCredit): self
    {
        $this->DurationCredit = $DurationCredit;

        return $this;
    }

    public function getUsername(): ?User
    {
        return $this->username;
    }

    public function setUsername(User $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPoints(): ?Wallet
    {
        return $this->points;
    }

    public function setPoints(?Wallet $points): self
    {
        $this->points = $points;

        return $this;
    }
}
