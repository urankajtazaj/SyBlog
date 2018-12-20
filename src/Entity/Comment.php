<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_created;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="comments")
     */
    private $post;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CommentVotes", mappedBy="comment")
     */
    private $commentVotes;

    public function __construct()
    {
        $this->commentVotes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->date_created;
    }

    public function setDateCreated(?\DateTimeInterface $date_created): self
    {
        $this->date_created = $date_created;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    /**
     * @return Collection|CommentVotes[]
     */
    public function getCommentVotes(): Collection
    {
        return $this->commentVotes;
    }

    public function addCommentVote(CommentVotes $commentVote): self
    {
        if (!$this->commentVotes->contains($commentVote)) {
            $this->commentVotes[] = $commentVote;
            $commentVote->setComment($this);
        }

        return $this;
    }

    public function removeCommentVote(CommentVotes $commentVote): self
    {
        if ($this->commentVotes->contains($commentVote)) {
            $this->commentVotes->removeElement($commentVote);
            // set the owning side to null (unless already changed)
            if ($commentVote->getComment() === $this) {
                $commentVote->setComment(null);
            }
        }

        return $this;
    }
}
