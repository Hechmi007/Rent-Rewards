<?php

namespace App\Entity;

use App\Repository\DonationRepository;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DonationRepository::class)]
class Donation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups("donation")]
    #[Assert\NotNull(message: "le valeur est NULL!!!")]
    #[Assert\PositiveOrZero(message: "le valeur indiquer est 0 ou negative !!!")]
    #[Assert\Length(
        min: 1,
        max: 50,
        minMessage: 'the points demanded must be at least {{ limit }} characters long',
        maxMessage: 'the points demanded  cannot be longer than {{ limit }} characters',
    )]
    private ?float $Pointsdonated = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("donation")]
    private ?\DateTimeInterface $datedonation = null;



    #[ORM\ManyToOne(inversedBy: 'donations')]
    private ?User $username = null;

    #[ORM\ManyToOne(inversedBy: 'donations')]
    private ?CharityDemand $title = null;

    
   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPointsdonated(): ?float
    {
        return $this->Pointsdonated;
    }

    public function setPointsdonated(float $Pointsdonated): self
    {
        $this->Pointsdonated = $Pointsdonated;

        return $this;
    }

    public function getDatedonation(): ?\DateTimeInterface
    {
        return $this->datedonation;
    }

    public function setDatedonation(\DateTimeInterface $datedonation): self
    {
        $this->datedonation = $datedonation;

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



    public function getTitle(): ?CharityDemand
    {

        return $this->title;
    }

    public function setTitle(?CharityDemand $title): self
    {

        $this->title = $title;

        return $this;

    }
    


   
   

}