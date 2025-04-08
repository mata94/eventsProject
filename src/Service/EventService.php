<?php

namespace App\Service;

use App\Entity\Event;
use App\Entity\Slot;
use App\Repository\EventRepository;
use App\Repository\SlotRepository;

class EventService
{
    public function __construct(
        private EventRepository $eventRepository,
        private SlotRepository $slotRepository,
        private EventStatusService $eventStatusService
    )
    {
    }

    /**
     * @return Event[] array
     */
    public function getAllActiveEvents(): array
    {
        $activeStatus = $this->eventStatusService->getActiveStatus();

        return $this->eventRepository->getEventsByStatus(
            $activeStatus
        );
    }

    /**
     * @param int $id
     * @return Slot[] array
     * @throws \Exception
     */
    public function getAllSlotsByEvent(Event $event): array
    {
        return $this->slotRepository->findByEvent($event);
    }

    /**
     * @param int $id
     * @return Event
     * @throws \Exception
     */
    public function eventOfId(int $id): Event
    {
        /** @var Event $event */
        $event = $this->eventRepository->findById($id);
        if($event === null){
            throw new \Exception(
                sprintf("Event %s not found", $id)
            );
        }

        if($event->isActive() === false){
            throw new \Exception(
                sprintf("Event %s is not active", $id)
            );
        }

        return $event;
    }

    /**
     * @param int $id
     * @return Event
     * @throws \Exception
     */
    public function getEventById(int $id): Event
    {
        /** @var Event $event */
        $event = $this->eventRepository->findById($id);
        if($event === null){
            throw new \Exception(
                sprintf("Event %s not found", $id)
            );
        }

        return $event;
    }

    /**
     * @return Event[] array
     */
    public function getAllEvents(): array
    {
        $events = $this->eventRepository->findAll();
        return $events;
    }
}
