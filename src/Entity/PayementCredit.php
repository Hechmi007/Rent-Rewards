<?php

namespace App\Entity;

use App\Repository\PayementCreditRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PayementCreditRepository::class)]
class PayementCredit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montantpaye = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateremboursement = null;

    #[ORM\ManyToOne(inversedBy: 'payementCredits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Credit $credit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontantpaye(): ?float
    {
        return $this->montantpaye;
    }

    public function setMontantpaye(float $montantpaye): self
    {
        $this->montantpaye = $montantpaye;

        return $this;
    }

    public function getDateremboursement(): ?\DateTimeInterface
    {
        return $this->dateremboursement;
    }

    public function setDateremboursement(\DateTimeInterface $dateremboursement): self
    {
        $this->dateremboursement = $dateremboursement;

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

    public function getCredit(): ?Credit
    {
        return $this->credit;
    }

    public function setCredit(Credit $credit): self
    {
        $this->credit = $credit;

        return $this;
    }
}
