<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le champ est vide !")]
    private ?string $nomproduit = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Precisez le prix en TND")]
    private ?float $prixproduit = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message:"Le champ est vide !")]
    private ?\DateTimeInterface $dateproduit = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Preisez un type pour votre produit")]
    private ?string $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomproduit(): ?string
    {
        return $this->nomproduit;
    }

    public function setNomproduit(string $nomproduit): self
    {
        $this->nomproduit = $nomproduit;

        return $this;
    }

    public function getPrixproduit(): ?float
    {
        return $this->prixproduit;
    }

    public function setPrixproduit(float $prixproduit): self
    {
        $this->prixproduit = $prixproduit;

        return $this;
    }

    public function getDateproduit(): ?\DateTimeInterface
    {
        return $this->dateproduit;
    }

    public function setDateproduit(\DateTimeInterface $dateproduit): self
    {
        $this->dateproduit = $dateproduit;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
