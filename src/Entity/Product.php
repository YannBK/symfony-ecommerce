<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank()]
    private ?string $name = null;

    #[ORM\Column(length: 60)]
    #[Assert\NotBlank()]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    private ?string $illustration = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    private ?string $subtitle = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotBlank()]
    private ?float $price = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'products')]
    #[ORM\JoinTable(name: 'product_category_category')]
    private ?Collection $category = null;

    #[ORM\Column]
    #[Assert\NotBlank()]
    private ?bool $isBest = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Comment::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Note::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $notes;
 
    #[ORM\Column(nullable: true)]
    private ?int $averageNote = null;

    #[ORM\Column(nullable: true)]
    private ?int $stars = null;

    #[ORM\Column(nullable: true)]
    private ?int $halfStar = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: OrderDetails::class)]
    #[ORM\JoinColumn(nullable: true)]
    private Collection $orderDetails;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->orderDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): ?Collection
    {
        return $this->category;
    }

    public function setCategory(?Collection $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function isIsBest(): ?bool
    {
        return $this->isBest;
    }

    public function setIsBest(bool $isBest): self
    {
        $this->isBest = $isBest;

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
            $comment->setProduct($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getProduct() === $this) {
                $comment->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Note>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->setProduct($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getProduct() === $this) {
                $note->setProduct(null);
            }
        }

        return $this;
    }

    public function getAverageNote(): ?int
    {
        return $this->averageNote;
    }

    public function setAverageNote(int $averageNote): self
    {
        $this->averageNote = $averageNote;

        return $this;
    }

    public function getStars(): ?int
    {
        return $this->stars;
    }

    public function setStars(int $stars): self
    {
        $this->stars = $stars;

        return $this;
    }
    
    public function getHalfStar(): ?int
    {
        return $this->halfStar;
    }

    public function setHalfStar(int $halfStar): self
    {
        $this->halfStar = $halfStar;

        return $this;
    }
    /**
     * @return Collection<int, OrderDetails>
     */
    public function getOrderDetails(): Collection
    {
        return $this->orderDetails;
    }

    public function addOrderDetail(OrderDetails $orderDetail): self
    {
        if (!$this->orderDetails->contains($orderDetail)) {
            $this->orderDetails->add($orderDetail);
            $orderDetail->setProduct($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetails $orderDetail): self
    {
        if ($this->orderDetails->removeElement($orderDetail)) {
            // set the owning side to null (unless already changed)
            if ($orderDetail->getProduct() === $this) {
                $orderDetail->setProduct(null);
            }
        }

        return $this;
    }
}
