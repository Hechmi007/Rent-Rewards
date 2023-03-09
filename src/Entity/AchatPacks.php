<?php

namespace App\Entity;

use App\Repository\AchatPacksRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AchatPacksRepository::class)]
class AchatPacks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("achat")]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("achat")]
    private ?\DateTimeInterface $Date = null;


    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Paquet $NamePaquet = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?FidelityCard $Numcarte = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

   

    public function getNamePaquet(): ?Paquet
    {
        return $this->NamePaquet;
    }

    public function setNamePaquet(?Paquet $NamePaquet): self
    {
        $this->NamePaquet = $NamePaquet;

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
}
