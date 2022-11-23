<?php

namespace App\Entity;

use App\Repository\MarketingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: MarketingRepository::class)]
class Marketing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank()]
    private ?string $title = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank()]
    private ?string $subtitle = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]
    private ?string $content = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank()]
    private ?int $place = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    private ?string $illustration = null;

    #[ORM\Column(length: 5)]
    #[Assert\NotBlank()]
    private ?string $imageSide = null;

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

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPlace(): ?int
    {
        return $this->place;
    }

    public function setPlace(?int $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getIllustration(): ?string
    {
        return $this->illustration;
    }

    public function setIllustration(string $illustration): self
    {
        $this->illustration = $illustration;

        return $this;
    }

    public function getImageSide(): ?string
    {
        return $this->imageSide;
    }

    public function setImageSide(string $imageSide): self
    {
        $this->imageSide = $imageSide;

        return $this;
    }
}
