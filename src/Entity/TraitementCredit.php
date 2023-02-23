<?php

namespace App\Entity;

use App\Repository\TraitementCreditRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TraitementCreditRepository::class)]
class TraitementCredit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Status = null;

    #[ORM\Column(length: 255)]
    private ?string $Penality = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Restab = null;

    #[ORM\Column]
    private ?float $Restant = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $username = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->Status;
    }

    public function setStatus(string $Status): self
    {
        $this->Status = $Status;

        return $this;
    }

    public function getPenality(): ?string
    {
        return $this->Penality;
    }

    public function setPenality(string $Penality): self
    {
        $this->Penality = $Penality;

        return $this;
    }

    public function getRestab(): ?string
    {
        return $this->Restab;
    }

    public function setRestab(?string $Restab): self
    {
        $this->Restab = $Restab;

        return $this;
    }

    public function getRestant(): ?float
    {
        return $this->Restant;
    }

    public function setRestant(float $Restant): self
    {
        $this->Restant = $Restant;

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
}
