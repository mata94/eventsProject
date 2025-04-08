<?php

namespace App\Entity;

use App\Helper\DateTimeHelper;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\HasLifecycleCallbacks()]
class SlotUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'slotUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private Slot $slot;

    #[ORM\ManyToOne(inversedBy: 'slotUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $qrCode = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $scanTime = null;

    public static function make(
        User $user,
        Slot $slot,
    ): self
    {
        $self = new self();
        $self->setUser($user);
        $self->setSlot($slot);

        return $self;
    }

    #[ORM\PrePersist]
    public function prePersist()
    {
        $time = DateTimeHelper::getNewForImmutable();
        $createdAt = DateTimeHelper::getNewUserTimeForImmutable($time);
        $this->setCreatedAt($createdAt);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getSlot(): Slot
    {
        return $this->slot;
    }

    public function setSlot(Slot $slot): static
    {
        $this->slot = $slot;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }


    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getQrCode(): ?string
    {
        return $this->qrCode;
    }

    public function setQrCode(?string $qrCode): static
    {
        $this->qrCode = $qrCode;

        return $this;
    }

    public function getScanTime(): ?\DateTimeInterface
    {
        return $this->scanTime;
    }

    public function setScanTime(?\DateTimeInterface $scanTime): static
    {
        $this->scanTime = $scanTime;

        return $this;
    }
}
