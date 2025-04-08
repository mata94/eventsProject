<?php

namespace App\Service;

use App\Entity\EventStatus;
use App\Repository\EventStatusRepository;

class EventStatusService
{
    public function __construct(
        private EventStatusRepository $eventStatusRepository
    )
    {
    }

    public function getActiveStatus(): EventStatus
    {
        return $this->eventStatusRepository->findByName(
            EventStatus::ACTIVE
        );
    }

    public function getInActiveStatus(): EventStatus
    {
        return $this->eventStatusRepository->findByName(
            EventStatus::INACTIVE
        );
    }
}
