<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $IDproduit = null;

    #[ORM\Column(length: 255)]
    private ?string $nomproduit = null;

    #[ORM\Column]
    private ?int $IDClient = null;

    #[ORM\Column]
    private ?float $prixproduit = null;

    #[ORM\Column]
    private ?float $TVA = null;

    #[ORM\Column]
    private ?float $totalfacture = null;

    #[ORM\Column]
    private ?int $IDLocataire = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateFacture = null;

    #[ORM\Column(length: 255)]
    private ?string $Adressfacture = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Products $IDProduit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIDproduit(): ?int
    {
        return $this->IDproduit;
    }

    public function setIDproduit(int $IDproduit): self
    {
        $this->IDproduit = $IDproduit;

        return $this;
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

    public function getIDClient(): ?int
    {
        return $this->IDClient;
    }

    public function setIDClient(int $IDClient): self
    {
        $this->IDClient = $IDClient;

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

    public function getTVA(): ?float
    {
        return $this->TVA;
    }

    public function setTVA(float $TVA): self
    {
        $this->TVA = $TVA;

        return $this;
    }

    public function getTotalfacture(): ?float
    {
        return $this->totalfacture;
    }

    public function setTotalfacture(float $totalfacture): self
    {
        $this->totalfacture = $totalfacture;

        return $this;
    }

    public function getIDLocataire(): ?int
    {
        return $this->IDLocataire;
    }

    public function setIDLocataire(int $IDLocataire): self
    {
        $this->IDLocataire = $IDLocataire;

        return $this;
    }

    public function getDateFacture(): ?\DateTimeInterface
    {
        return $this->DateFacture;
    }

    public function setDateFacture(\DateTimeInterface $DateFacture): self
    {
        $this->DateFacture = $DateFacture;

        return $this;
    }

    public function getAdressfacture(): ?string
    {
        return $this->Adressfacture;
    }

    public function setAdressfacture(string $Adressfacture): self
    {
        $this->Adressfacture = $Adressfacture;

        return $this;
    }
}
