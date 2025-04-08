<?php

namespace App\Application\SlotUser\Command;

use App\Entity\Slot;

class SlotConfirmArrivalCommand
{
    public function __construct(
        private string $fullName,
        private string $email,
        private int $slotId
    ){}

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getSlotId(): int
    {
        return $this->slotId;
    }

    public function setSlotId(int $slotId): void
    {
        $this->slotId = $slotId;
    }
}
