<?php

namespace App\Entity;

use App\Repository\CharityDemandRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharityDemandRepository::class)]
class CharityDemand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $receiver = null;

    #[ORM\Column]
    private ?float $pointsdemanded = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datedemand = null;

    #[ORM\ManyToOne(inversedBy: 'charityDemands')]
    private ?User $username = null;

    #[ORM\ManyToOne(inversedBy: 'charityDemands')]
    private ?Charitycategory $category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getReceiver(): ?string
    {
        return $this->receiver;
    }

    public function setReceiver(string $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function getPointsdemanded(): ?float
    {
        return $this->pointsdemanded;
    }

    public function setPointsdemanded(float $pointsdemanded): self
    {
        $this->pointsdemanded = $pointsdemanded;

        return $this;
    }

    public function getDatedemand(): ?\DateTimeInterface
    {
        return $this->datedemand;
    }

    public function setDatedemand(\DateTimeInterface $datedemand): self
    {
        $this->datedemand = $datedemand;

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

    public function getCategory(): ?Charitycategory
    {
        return $this->category;
    }

    public function setCategory(?Charitycategory $category): self
    {
        $this->category = $category;

        return $this;
    }
}
