<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private \DateTimeInterface $date;

    /**
     * @var Collection<int, Slot>
     */
    #[ORM\OneToMany(targetEntity: Slot::class, mappedBy: 'event')]
    private Collection $slots;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private EventStatus $status;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ImagePath = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;

    public static function make(
        string $name,
        EventStatus $status
    ): self
    {
        $self = new self();
        $self->setName($name);
        $self->setStatus($status);
        return $self;
    }

    public function __construct()
    {
        $this->slots = new ArrayCollection();
    }

    public function isActive(): bool
    {
        return $this->getStatus()->isActive();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    /**
     * @return Collection<int, Slot>
     */
    public function getSlots(): Collection
    {
        return $this->slots;
    }

    public function addSlot(Slot $slot): static
    {
        if (!$this->slots->contains($slot)) {
            $this->slots->add($slot);
            $slot->setEvent($this);
        }

        return $this;
    }

    public function removeSlot(Slot $slot): static
    {
        if ($this->slots->removeElement($slot)) {
            // set the owning side to null (unless already changed)
            if ($slot->getEvent() === $this) {
                $slot->setEvent($this);
            }
        }

        return $this;
    }

    public function getStatus(): EventStatus
    {
        return $this->status;
    }

    public function setStatus(EventStatus $status): static
    {
        $this->status = $status;

        return $this;
    }


    public function getImagePath(): ?string
    {
        return $this->ImagePath;
    }

    public function setImagePath(?string $ImagePath): static
    {
        $this->ImagePath = $ImagePath;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }
}
