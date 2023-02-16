<?php

namespace App\Entity;

use App\Repository\CharitycategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharitycategoryRepository::class)]
class Charitycategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Type = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateCharity = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: CharityDemand::class)]
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

    public function setType(string $Type): self
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
