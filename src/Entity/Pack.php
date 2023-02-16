<?php

namespace App\Entity;

use App\Repository\PackRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PackRepository::class)]
class Pack
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'packs')]
    private ?FidelityCard $numcarte = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumcarte(): ?FidelityCard
    {
        return $this->numcarte;
    }

    public function setNumcarte(?FidelityCard $numcarte): self
    {
        $this->numcarte = $numcarte;

        return $this;
    }
}
