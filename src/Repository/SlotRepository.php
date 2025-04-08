<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Slot;

class SlotRepository extends BaseRepository
{
    protected function getEntityName()
    {
        return Slot::class;
    }

    /**
     * @param Event $event
     * @return Slot[]
     */
    public function findByEvent(Event $event): array
    {
        /** @var Slot[] */
        return $this->findBy([
            "event" => $event
        ]);
    }
}
