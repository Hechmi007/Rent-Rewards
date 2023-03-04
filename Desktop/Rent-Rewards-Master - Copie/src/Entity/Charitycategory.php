<?php

namespace App\Entity;

use App\Repository\CharitycategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CharitycategoryRepository::class)]
class Charitycategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("charitycategory")]
  
    #[Assert\Length(
        min: 4,
        max: 50,
        minMessage: 'the type must be at least {{ limit }} characters long',
        maxMessage: 'the type  cannot be longer than {{ limit }} characters',
    )]
    #[Assert\NotBlank(message: "le champ est vide!!!")]
    private ?string $Type = null;
    
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("charitycategory")]
    // #[Assert\Date(message: "insert valid date!!!")]  
   
    private ?\DateTimeInterface $DateCharity = null;
    
    #[ORM\OneToMany(mappedBy: 'category', targetEntity: CharityDemand::class, cascade: ["remove"])]
    
    private Collection $charityDemands;

    public function __construct()
    {
        $this->charityDemands = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(?string $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    public function getDateCharity(): ?\DateTimeInterface
    {
        return $this->DateCharity;
    }

    public function setDateCharity(\DateTimeInterface $DateCharity): self
    {
        $this->DateCharity = $DateCharity;

        return $this;
    }
    public function __toString() {
        return $this->Type;
    }

    /**
     * @return Collection<int, CharityDemand>
     */
    public function getCharityDemands(): Collection
    {
        return $this->charityDemands;
    }

    public function addCharityDemand(CharityDemand $charityDemand): self
    {
        if (!$this->charityDemands->contains($charityDemand)) {
            $this->charityDemands->add($charityDemand);
            $charityDemand->setCategory($this);
        }

        return $this;
    }

    public function removeCharityDemand(CharityDemand $charityDemand): self
    {
        if ($this->charityDemands->removeElement($charityDemand)) {
            // set the owning side to null (unless already changed)
            if ($charityDemand->getCategory() === $this) {
                $charityDemand->setCategory(null);
            }
        }

        return $this;
    }
}
