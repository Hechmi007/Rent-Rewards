<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface , PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

        
    #[ORM\Column(type:"string", nullable:true)] 
    private $resetToken;
    
    #[ORM\Column(type:"boolean")] 
    private $EmailVerified = false;

    #[ORM\Column(type:"boolean")]
    private $banned= false;

    #[ORM\Column(type:"string", length:55, nullable:true)] 
    private $confirmationToken;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"please insert you username")]
    private ?string $username = null;

    #[ORM\Column(type: Types::ARRAY)]
    #[Assert\NotBlank(message:"you should choose")]
    private array $roles = [];

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"please insert a username")]
    private ?string $email = null;

    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"this field should not be empty")]
    private ?string $password = null;


    #[ORM\OneToOne(targetEntity: Wallet::class, mappedBy:'username', cascade: ['persist', 'remove'])]
    private ?Wallet $wallet = null;

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
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    public function setWallet(?Wallet $wallet): self
    {
        $this->wallet = $wallet;

        // set the owning side of the relation if necessary
        if ($this !== $wallet->getUsername()) {
            $wallet->setUsername($this);
        }

        return $this;
    }
    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }
    public function getEmailVerified(): bool
    {
        return $this->EmailVerified;
    }

    public function setEmailVerified(bool $EmailVerified): self
    {
        $this->EmailVerified = $EmailVerified;

        return $this;
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken(?string $confirmationToken): self
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }
    public function isBanned(): bool
    {
        return $this->banned;
    }
    public function getBanned(): bool
    {
        return $this->banned;
    }
    public function setBanned(bool $banned): self
    {
        $this->banned = $banned;

        return $this;
    }
    public function __toString(): string
{
    return $this->getUsername();
}


}
