<?php

namespace App\Entity;

use App\Repository\EventStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class EventStatus
{
    public const ACTIVE = "Active";
    public const INACTIVE = "Inactive";

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $name;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'status')]
    private Collection $events;

    public static function make(
        string $name
    ): self
    {
        $self = new self();
        $self->setName($name);

        return $self;
    }

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function isActive(): bool
    {
        if($this->name !== self::ACTIVE){
            return false;
        }
        return true;
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

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setStatus($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getStatus() === $this) {
                $event->setStatus($this);
            }
        }

        return $this;
    }
}
