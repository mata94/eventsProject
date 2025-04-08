<?php

namespace App\Repository;

use App\Entity\EventStatus;

class EventStatusRepository extends BaseRepository
{
    protected function getEntityName()
    {
        return EventStatus::class;
    }

    public function findByName(string $name): ?EventStatus
    {
        return $this->findOneBy(
            [
                'name' => $name
            ]
        );
    }
}
