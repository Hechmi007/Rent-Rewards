<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductsRepository::class)]
class Products
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $productname = null;

    #[ORM\Column]
    private ?float $RentPrice = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Availabilitydate = null;

    #[ORM\Column(length: 255)]
    private ?string $ProductType = null;

    #[ORM\Column(length: 255)]
    private ?string $ProductPicture = null;

    #[ORM\Column(length: 255)]
    private ?string $ProductAdress = null;

    #[ORM\Column]
    private ?bool $StillAvailable = null;

    #[ORM\OneToMany(mappedBy: 'categoryname', targetEntity: ProductCategory::class)]
    private Collection $productcategory;

    public function __construct()
    {
        $this->productcategory = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductname(): ?string
    {
        return $this->productname;
    }

    public function setProductname(string $productname): self
    {
        $this->productname = $productname;

        return $this;
    }

    public function getRentPrice(): ?float
    {
        return $this->RentPrice;
    }

    public function setRentPrice(float $RentPrice): self
    {
        $this->RentPrice = $RentPrice;

        return $this;
    }

    public function getAvailabilitydate(): ?\DateTimeInterface
    {
        return $this->Availabilitydate;
    }

    public function setAvailabilitydate(\DateTimeInterface $Availabilitydate): self
    {
        $this->Availabilitydate = $Availabilitydate;

        return $this;
    }

    public function getProductType(): ?string
    {
        return $this->ProductType;
    }

    public function setProductType(string $ProductType): self
    {
        $this->ProductType = $ProductType;

        return $this;
    }

    public function getProductPicture(): ?string
    {
        return $this->ProductPicture;
    }

    public function setProductPicture(string $ProductPicture): self
    {
        $this->ProductPicture = $ProductPicture;

        return $this;
    }

    public function getProductAdress(): ?string
    {
        return $this->ProductAdress;
    }

    public function setProductAdress(string $ProductAdress): self
    {
        $this->ProductAdress = $ProductAdress;

        return $this;
    }

    public function isStillAvailable(): ?bool
    {
        return $this->StillAvailable;
    }

    public function setStillAvailable(bool $StillAvailable): self
    {
        $this->StillAvailable = $StillAvailable;

        return $this;
    }

    /**
     * @return Collection<int, ProductCategory>
     */
    public function getProductcategory(): Collection
    {
        return $this->productcategory;
    }

    public function addProductcategory(ProductCategory $productcategory): self
    {
        if (!$this->productcategory->contains($productcategory)) {
            $this->productcategory->add($productcategory);
            $productcategory->setCategoryname($this);
        }

        return $this;
    }

    public function removeProductcategory(ProductCategory $productcategory): self
    {
        if ($this->productcategory->removeElement($productcategory)) {
            // set the owning side to null (unless already changed)
            if ($productcategory->getCategoryname() === $this) {
                $productcategory->setCategoryname(null);
            }
        }

        return $this;
    }
}
