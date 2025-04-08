<?php

namespace App\Service;

use App\Entity\Slot;
use App\Repository\SlotRepository;

class SlotService
{
    public function __construct(
        private SlotRepository $slotRepository
    ){}

    /**
     * @param int $slotId
     * @return Slot
     * @throws \Exception
     */
    public function getSlotById(int $slotId): Slot
    {
        /** @var Slot $slot */
        $slot = $this->slotRepository->findById($slotId);
        if($slot === null){
            throw new \Exception(
                \sprintf("Slot %s not found!", $slotId)
            );
        }

        if($slot->getEvent()->isActive() === false){
            throw new \Exception(
                \sprintf("Slot %s has not active event!", $slotId)
            );
        }
        return $slot;
    }

    /**
     * @param int $slotId
     * @return Slot
     * @throws \Exception
     */
    public function getSlotOfId(int $slotId): Slot
    {

        /** @var Slot $slot */
        $slot = $this->slotRepository->findById($slotId);
        if($slot === null){
            throw new \Exception(
                \sprintf("Slot %s not found!", $slotId)
            );
        }
        return $slot;
    }
}
