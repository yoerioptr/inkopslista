<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BasketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BasketRepository::class)]
#[ORM\HasLifecycleCallbacks]
final class Basket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, BasketItem>
     */
    #[ORM\OneToMany(targetEntity: BasketItem::class, mappedBy: 'basket', orphanRemoval: true, cascade: ['persist'])]
    private Collection $items;

    #[ORM\Column]
    private ?\DateTimeImmutable $created = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\ManyToOne(inversedBy: 'baskets')]
    private ?User $Author = null;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, BasketItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(BasketItem $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setBasket($this);
        }

        return $this;
    }

    public function removeItem(BasketItem $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getBasket() === $this) {
                $item->setBasket(null);
            }
        }

        return $this;
    }

    public function getCreated(): ?\DateTimeImmutable
    {
        return $this->created;
    }

    public function setCreated(\DateTimeImmutable $created): static
    {
        $this->created = $created;

        return $this;
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->created = new \DateTimeImmutable();
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->Author;
    }

    public function setAuthor(?User $Author): static
    {
        $this->Author = $Author;

        return $this;
    }

    public function productsInCart(): ArrayCollection
    {
        $items = array_filter(
            $this->items->toArray(),
            fn(BasketItem $item) => $item->isInCart(),
        );

        return new ArrayCollection($items);
    }

    public function productsToPick(): ArrayCollection
    {
        $items = array_filter(
            $this->items->toArray(),
            fn(BasketItem $item) => !$item->isInCart(),
        );

        return new ArrayCollection($items);
    }
}
