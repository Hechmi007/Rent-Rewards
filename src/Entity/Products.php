<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductsRepository::class)]
class Products
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message:"Precisez le nom")]
    #[ORM\Column(length: 255)]
    private ?string $productname = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Precisez le prix en TND")]
    private ?float $RentPrice = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message:"Select date !!")]
    private ?\DateTimeInterface $Availabilitydate = null;
    
    #[Assert\NotBlank(message:"Precisez le type")]
    #[ORM\Column(length: 255)]
    private ?string $ProductType = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Image non valide !")]
    private ?string $ProductPicture = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le champ est vide !")]
    private ?string $ProductAdress = null;

    #[ORM\Column]
    private ?bool $StillAvailable = null;

    #[ORM\OneToMany(mappedBy: 'categoryname', targetEntity: ProductsCategory::class)]
    private Collection $productscategory;

    #[ORM\ManyToOne(inversedBy: 'productname')]
    private ?ProductsCategory $productsCategory = null;

    #[ORM\OneToMany(mappedBy: 'products', targetEntity: ProductsCategory::class)]
    private Collection $category;

    public function __construct()
    {
        $this->productcategory = new ArrayCollection();
        $this->category = new ArrayCollection();
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

    public function setProductPictureFile($ProductPictureFile)
    {
        $this->ProductPictureFile = $ProductPictureFile;

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

    public function getProductsCategory(): ?ProductsCategory
    {
        return $this->productsCategory;
    }

    public function setProductsCategory(?ProductsCategory $productsCategory): self
    {
        $this->productsCategory = $productsCategory;

        return $this;
    }

    /**
     * @return Collection<int, ProductsCategory>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(ProductsCategory $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category->add($category);
            $category->setProducts($this);
        }

        return $this;
    }

    public function removeCategory(ProductsCategory $category): self
    {
        if ($this->category->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getProducts() === $this) {
                $category->setProducts(null);
            }
        }

        return $this;
    }
}
