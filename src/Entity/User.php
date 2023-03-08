<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $roles = [];

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'username', targetEntity: Post::class)]
    private Collection $Author;

    #[ORM\OneToMany(mappedBy: 'username', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'username', targetEntity: CharityDemand::class)]
    private Collection $charityDemands;

    #[ORM\OneToMany(mappedBy: 'username', targetEntity: Donation::class)]
    private Collection $donations;

    public function __construct()
    {
        $this->Author = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->charityDemands = new ArrayCollection();
        $this->donations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getAuthor(): Collection
    {
        return $this->Author;
    }

    public function addAuthor(Post $author): self
    {
        if (!$this->Author->contains($author)) {
            $this->Author->add($author);
            $author->setUsername($this);
        }

        return $this;
    }

    public function removeAuthor(Post $author): self
    {
        if ($this->Author->removeElement($author)) {
            // set the owning side to null (unless already changed)
            if ($author->getUsername() === $this) {
                $author->setUsername(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setUsername($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUsername() === $this) {
                $comment->setUsername(null);
            }
        }

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
            $charityDemand->setUsername($this);
        }

        return $this;
    }

    public function removeCharityDemand(CharityDemand $charityDemand): self
    {
        if ($this->charityDemands->removeElement($charityDemand)) {
            // set the owning side to null (unless already changed)
            if ($charityDemand->getUsername() === $this) {
                $charityDemand->setUsername(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Donation>
     */
    public function getDonations(): Collection
    {
        return $this->donations;
    }

    public function addDonation(Donation $donation): self
    {
        if (!$this->donations->contains($donation)) {
            $this->donations->add($donation);
            $donation->setUsername($this);
        }

        return $this;
    }

    public function removeDonation(Donation $donation): self
    {
        if ($this->donations->removeElement($donation)) {
            // set the owning side to null (unless already changed)
            if ($donation->getUsername() === $this) {
                $donation->setUsername(null);
            }
        }

        return $this;
    }
}
