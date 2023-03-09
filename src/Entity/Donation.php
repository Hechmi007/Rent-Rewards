<?php

namespace App\Entity;

use App\Repository\DonationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DonationRepository::class)]
class Donation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $Pointsdonated = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datedonation = null;

    #[ORM\ManyToOne(inversedBy: 'donations')]
    private ?User $username = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?CharityDemand $title = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPointsdonated(): ?float
    {
        return $this->Pointsdonated;
    }

    public function setPointsdonated(float $Pointsdonated): self
    {
        $this->Pointsdonated = $Pointsdonated;

        return $this;
    }

    public function getDatedonation(): ?\DateTimeInterface
    {
        return $this->datedonation;
    }

    public function setDatedonation(\DateTimeInterface $datedonation): self
    {
        $this->datedonation = $datedonation;

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

    public function getTitle(): ?CharityDemand
    {
        return $this->title;
    }

    public function setTitle(?CharityDemand $title): self
    {
        $this->title = $title;

        return $this;
    }
}
