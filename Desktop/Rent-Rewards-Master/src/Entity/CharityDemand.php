<?php

namespace App\Entity;
use App\Entity\Charitycategory;
use App\Repository\CharityDemandRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CharityDemandRepository::class)]
class CharityDemand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "le champ est vide!!!")]
    #[Assert\Length(
        min: 4,
        max: 50,
        minMessage: 'the charity title  must be at least {{ limit }} characters long',
        maxMessage: 'the charity title  cannot be longer than {{ limit }} characters',
    )]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "le champ est vide!!!")]
    #[Assert\Length(
        min: 4,
        max: 50,
        minMessage: 'the receiver name must be at least {{ limit }} characters long',
        maxMessage: 'the receiver name cannot be longer than {{ limit }} characters',
    )]
    private ?string $receiver = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero(message: "le valeur indiquer est 0 ou negative !!!")]
    #[Assert\Length(
        min: 4,
        max: 50,
        minMessage: 'the points demanded must be at least {{ limit }} characters long',
        maxMessage: 'the points demanded  cannot be longer than {{ limit }} characters',
    )]
    #[Assert\NotBlank(message: "le champ est vide!!!")]
    #[Assert\NotNull(message: "le valeur est NULL!!!")]
    private ?float $pointsdemanded = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    
    private ?\DateTimeInterface $datedemand = null;

    #[ORM\ManyToOne(inversedBy: 'charityDemands')]
    private ?User $username = null;

    #[ORM\ManyToOne(inversedBy: 'charityDemands')]
    private ?Charitycategory $category = null;

   /*  #[ORM\Column(length: 255)]
    private ?string $uploadFile = null; */

    /* #[ORM\Column]
    private ?bool $isValid = null; */

   
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
    public function __toString()
    {
        return $this ->title;
    }

  /*   public function isIsValid(): ?bool
    {
        return $this->isValid;
    }

    public function setIsValid(bool $isValid): self
    {
        $this->isValid = $isValid;

        return $this;
    } */

 /*  public function getUploadFile(): ?string
  {
      return $this->uploadFile;
  }

  public function setUploadFile(string $uploadFile): self
  {
      $this->uploadFile = $uploadFile;

      return $this;
  } */
   
}