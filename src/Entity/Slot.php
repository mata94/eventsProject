<?php

namespace App\Entity;

use App\Enum\SlotHtmlClassEnum;
use App\Helper\DateTimeHelper;
use App\Repository\SlotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;

#[ORM\Entity()]
class Slot
{
    public const FULL = 'full';

    public const AVAILABLE = 'available';
    public const ACTIVE = 'active';
    public const FINISHED = 'finished';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $timeStart;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $timeEnd;

    #[ORM\Column]
    private int $seats;

    #[ORM\ManyToOne(inversedBy: 'slots')]
    #[ORM\JoinColumn(nullable: false)]
    private Event $event;

    /**
     * @var Collection<int, SlotUser>
     */
    #[ORM\OneToMany(targetEntity: SlotUser::class, mappedBy: 'slot')]
    private Collection $slotUsers;

    private ?string $statusName = null;
    private ?string $slotTime = null;

    public static function make(
        Event $event,
        int $seats,
        \DateTime $timeStart,
        \DateTime $timeEnd
    ): self
    {
        $self = new self();
        $self->setEvent($event);
        $self->setSeats($seats);
        $self->setTimeStart($timeStart);
        $self->setTimeEnd($timeEnd);

        return $self;
    }

    public function __construct()
    {
        $this->slotUsers = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTimeStart(): \DateTimeInterface
    {
        return $this->timeStart;
    }

    public function setTimeStart(\DateTimeInterface $timeStart): static
    {
        $this->timeStart = $timeStart;

        return $this;
    }

    public function getSlotStatusName(): string
    {
        if($this->statusName !== null){
            return $this->statusName;
        }

        $now = DateTimeHelper::getNewForSlot('now', 'UTC', 2);
        $end = $this->getTimeEnd();

        if($now > $end){
            $this->statusName = self::FINISHED;
            return self::FINISHED;
        }

        # If not finished, but full
        if($this->isSlotFull()){
            $this->statusName = self::FULL;
            return self::FULL;
        }

        if($now < $end){
            $this->statusName = self::AVAILABLE;
            return self::AVAILABLE;
        }
        $this->statusName = self::ACTIVE;
        return self::ACTIVE;
    }

    /**
     * @return bool
     */
    public function isFinished(): bool
    {
        $status = $this->getSlotStatusName();
        if($status == 'finished'){
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isAvailable(): bool
    {
        $status = $this->getSlotStatusName();
        if($status == 'available'){
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getSlotHtmlClassForStatus(): string
    {
        $status = $this->getSlotStatusName();

        if($status === self::AVAILABLE){
            return SlotHtmlClassEnum::AVAILABLE_CLASS;
        }elseif ($status === self::FULL){
            return SlotHtmlClassEnum::FULL_CLASS;
        }elseif ($status === self::FINISHED){
            return SlotHtmlClassEnum::FINISHED_CLASS;
        }

        return SlotHtmlClassEnum::DEFAULT_CLASS;
    }

    /**
     * @return string
     */
    public function getSlotAdminStatus(): string
    {
        $status = $this->getSlotStatusName();

        if($status === self::AVAILABLE){
            return SlotHtmlClassEnum::ADMIN_SLOT_VALID;
        }elseif ($status === self::FULL){
            return SlotHtmlClassEnum::ADMIN_SLOT_EXPIRED;
        }elseif ($status === self::FINISHED){
            return SlotHtmlClassEnum::ADMIN_SLOT_EXPIRED;
        }

        return SlotHtmlClassEnum::ADMIN_SLOT_VALID;
    }

    /**
     * @return string
     */
    public function getSlotHtmlClassForTime(): string
    {
        $status = $this->getSlotStatusName();

        if($status === self::AVAILABLE){
            return SlotHtmlClassEnum::AVAILABLE_TIME_CLASS;
        }elseif ($status === self::FULL){
            return SlotHtmlClassEnum::FULL_TIME_CLASS;
        }elseif ($status === self::FINISHED){
            return SlotHtmlClassEnum::FINISHED_TIME_CLASS;
        }

        return SlotHtmlClassEnum::DEFAULT_TIME_CLASS;
    }

    public function isSlotFull(): bool
    {
        if($this->getSlotUsers()->count() === $this->getSeats()){
            return true;
        }
        return false;
    }

    public function getSeatsStats(): string
    {
        $subscribedUsers = $this->getSlotUsers()->count();

        return \sprintf(
            '%s/%s', $subscribedUsers, $this->getSeats()
        );
    }

    public function getSlotTimePeriod(): string
    {
        return \sprintf(
            '%s-%s',
            $this->getTimeStart()->format("H:i"),
            $this->getTimeEnd()->format("H:i")
        );
    }

    public function getTimeEnd(): \DateTimeInterface
    {
        return $this->timeEnd;
    }

    public function setTimeEnd(\DateTimeInterface $timeEnd): static
    {
        $this->timeEnd = $timeEnd;
        return $this;
    }

    public function getSeats(): int
    {
        return $this->seats;
    }

    public function setSeats(int $seats): static
    {
        $this->seats = $seats;

        return $this;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function setEvent(Event $event): static
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @return Collection<int, SlotUser>
     */
    public function getSlotUsers(): Collection
    {
        return $this->slotUsers;
    }

    public function addSlotUser(SlotUser $slotUser): static
    {
        if (!$this->slotUsers->contains($slotUser)) {
            $this->slotUsers->add($slotUser);
            $slotUser->setSlot($this);
        }

        return $this;
    }

    public function removeSlotUser(SlotUser $slotUser): static
    {
        if ($this->slotUsers->removeElement($slotUser)) {
            // set the owning side to null (unless already changed)
            if ($slotUser->getSlot() === $this) {
                $slotUser->setSlot(null);
            }
        }

        return $this;
    }
}
