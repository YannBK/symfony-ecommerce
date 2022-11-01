<?php

namespace App\Entity;

use App\Repository\OpinionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OpinionRepository::class)]
class Opinion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'opinions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'opinions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Comment $comment = null;

    #[ORM\Column(nullable: true)]
    private ?bool $opinion = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(?Comment $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function isOpinion(): ?bool
    {
        return $this->opinion;
    }

    public function setOpinion(?bool $opinion): self
    {
        $this->opinion = $opinion;

        return $this;
    }

    // public function countOpinions(): ?array
    // {
    //     if($this->opinion === true) {
    //         return array('positive' => 1, 'negative' => 0);
    //     } else if($this->opinion === false) {
    //         return array('positive' => 0, 'negative' => 1);
    //     }
    //     return array('positive' => 0, 'negative' => 0);
    // }
}
