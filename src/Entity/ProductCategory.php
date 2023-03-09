<?php

namespace App\Entity;

use App\Repository\ProductCategoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductCategoryRepository::class)]
class ProductCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $namecateg = null;

    #[ORM\Column(length: 255)]
    private ?string $typecategory = null;

    #[ORM\ManyToOne(inversedBy: 'productcategory')]
    private ?Products $categoryname = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNamecateg(): ?string
    {
        return $this->namecateg;
    }

    public function setNamecateg(string $namecateg): self
    {
        $this->namecateg = $namecateg;

        return $this;
    }

    public function getTypecategory(): ?string
    {
        return $this->typecategory;
    }

    public function setTypecategory(string $typecategory): self
    {
        $this->typecategory = $typecategory;

        return $this;
    }

    public function getCategoryname(): ?Products
    {
        return $this->categoryname;
    }

    public function setCategoryname(?Products $categoryname): self
    {
        $this->categoryname = $categoryname;

        return $this;
    }
}
