<?php

namespace App\Entity;

use App\Repository\WalletRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WalletRepository::class)]
class Wallet
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer", options: ["unsigned" => true, "auto_increment" => 1000])]
    private ?int $id = null;
    
    #[ORM\Column(nullable: true)]
    private ?float $solde = null;

    #[ORM\Column(nullable: true)]
    private ?float $points = null;

    #[ORM\Column(nullable: true,length: 255)]
    private ?string $plan = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $username = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSolde(): ?float
    {
        return $this->solde;
    }

    public function setSolde(float $solde): self
    {
        $this->solde = $solde;

        return $this;
    }

    public function getPoints(): ?float
    {
        return $this->points;
    }

    public function setPoints(float $points): self
    {
        $this->points = $points;

        return $this;
    }

    public function getPlan(): ?string
    {
        return $this->plan;
    }

    public function setPlan(string $plan): self
    {
        $this->plan = $plan;

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
}
