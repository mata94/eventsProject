<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\EventStatus;

class EventRepository extends BaseRepository
{
   protected function getEntityName()
   {
       return Event::class;
   }

    /**
     * @return Event[]
     */
   public function getEventsByStatus(EventStatus $eventStatus): array
   {
       return $this->findBy(
           [
               'status' => $eventStatus
           ]
       );
   }
}
