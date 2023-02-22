<?php

namespace App\Entity;

use App\Repository\ProductsCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductsCategoryRepository::class)]
class ProductsCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le champ est vide !")]
    private ?string $Categoryname = null;

    #[ORM\Column]
    private ?bool $Type = null;

    #[ORM\OneToMany(mappedBy: 'productsCategory', targetEntity: Products::class)]
    private Collection $productname;

    #[ORM\ManyToOne(inversedBy: 'category')]
    private ?Products $products = null;

    public function __construct()
    {
        $this->productname = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoryname(): ?string
    {
        return $this->Categoryname;
    }

    public function setCategoryname(string $Categoryname): self
    {
        $this->Categoryname = $Categoryname;

        return $this;
    }

    public function isType(): ?bool
    {
        return $this->Type;
    }

    public function setType(bool $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    /**
     * @return Collection<int, Products>
     */
    public function getProductname(): Collection
    {
        return $this->productname;
    }

    public function addProductname(Products $productname): self
    {
        if (!$this->productname->contains($productname)) {
            $this->productname->add($productname);
            $productname->setProductsCategory($this);
        }

        return $this;
    }

    public function removeProductname(Products $productname): self
    {
        if ($this->productname->removeElement($productname)) {
            // set the owning side to null (unless already changed)
            if ($productname->getProductsCategory() === $this) {
                $productname->setProductsCategory(null);
            }
        }

        return $this;
    }

    public function getProducts(): ?Products
    {
        return $this->products;
    }

    public function setProducts(?Products $products): self
    {
        $this->products = $products;

        return $this;
    }
    public function __toString() {
        return $this->Categoryname;
    }

}
