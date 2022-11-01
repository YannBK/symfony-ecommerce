<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'answers')]
    private ?self $answerTo = null;

    #[ORM\OneToMany(mappedBy: 'answerTo', targetEntity: self::class)]
    private Collection $answers;

    #[ORM\Column(nullable: true)]
    private ?string $days = null;

    #[ORM\OneToMany(mappedBy: 'comment', targetEntity: Opinion::class)]
    private Collection $opinions;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $lastOpinion = null;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->opinions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getAnswerTo(): ?self
    {
        return $this->answerTo;
    }

    public function setAnswerTo(?self $answerTo): self
    {
        $this->answerTo = $answerTo;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(self $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setAnswerTo($this);
        }

        return $this;
    }

    public function removeAnswer(self $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getAnswerTo() === $this) {
                $answer->setAnswerTo(null);
            }
        }

        return $this;
    }

    // public function getNumberOfDaysPassed($com): string
    // {
    //     $time = new \DateTime('now');

    //     $days = $time->diff($com->getCreatedAt());

    //     return $days->format('%R%a days');
    // }

    public function getDays(): ?string
    {
        return $this->days;
    }

    public function setDays(?string $days): self
    {
        $this->days = $days;

        return $this;
    }

    /**
     * @return Collection<int, Opinion>
     */
    public function getOpinions(): Collection
    {
        return $this->opinions;
    }

    public function addOpinion(Opinion $opinion): self
    {
        if (!$this->opinions->contains($opinion)) {
            $this->opinions->add($opinion);
            $opinion->setComment($this);
        }

        return $this;
    }

    public function removeOpinion(Opinion $opinion): self
    {
        if ($this->opinions->removeElement($opinion)) {
            // set the owning side to null (unless already changed)
            if ($opinion->getComment() === $this) {
                $opinion->setComment(null);
            }
        }

        return $this;
    }

    public function getLastOpinion(): ?array
    {
        return $this->lastOpinion;
    }

    public function setLastOpinion(?array $lastOpinion): self
    {
        $this->lastOpinion = $lastOpinion;

        return $this;
    }
}
