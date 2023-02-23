<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $contentcomment = null;

    #[ORM\Column]
    private ?int $upvotes = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createdatcomment = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $IDPost = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $username = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContentcomment(): ?string
    {
        return $this->contentcomment;
    }

    public function setContentcomment(string $contentcomment): self
    {
        $this->contentcomment = $contentcomment;

        return $this;
    }

    public function getUpvotes(): ?int
    {
        return $this->upvotes;
    }

    public function setUpvotes(int $upvotes): self
    {
        $this->upvotes = $upvotes;

        return $this;
    }

    public function getCreatedatcomment(): ?\DateTimeInterface
    {
        return $this->createdatcomment;
    }

    public function setCreatedatcomment(\DateTimeInterface $createdatcomment): self
    {
        $this->createdatcomment = $createdatcomment;

        return $this;
    }

    public function getIDPost(): ?Post
    {
        return $this->IDPost;
    }

    public function setIDPost(?Post $IDPost): self
    {
        $this->IDPost = $IDPost;

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
}
