<?php

namespace App\Entity;

use App\Repository\PacksRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PacksRepository::class)]
class Packs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nompacks = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datedebutpacks = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datefinpacks = null;

    #[ORM\Column]
    private ?bool $etatpacks = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNompacks(): ?string
    {
        return $this->nompacks;
    }

    public function setNompacks(string $nompacks): self
    {
        $this->nompacks = $nompacks;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDatedebutpacks(): ?\DateTimeInterface
    {
        return $this->datedebutpacks;
    }

    public function setDatedebutpacks(\DateTimeInterface $datedebutpacks): self
    {
        $this->datedebutpacks = $datedebutpacks;

        return $this;
    }

    public function getDatefinpacks(): ?\DateTimeInterface
    {
        return $this->datefinpacks;
    }

    public function setDatefinpacks(\DateTimeInterface $datefinpacks): self
    {
        $this->datefinpacks = $datefinpacks;

        return $this;
    }

    public function isEtatpacks(): ?bool
    {
        return $this->etatpacks;
    }

    public function setEtatpacks(bool $etatpacks): self
    {
        $this->etatpacks = $etatpacks;

        return $this;
    }
}
