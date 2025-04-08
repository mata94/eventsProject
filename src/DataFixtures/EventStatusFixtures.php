<?php

namespace App\DataFixtures;

use App\Entity\EventStatus;
use App\Repository\EventStatusRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EventStatusFixtures extends Fixture
{

    public function __construct(
        private EventStatusRepository $eventStatusRepository
    ){}

    public function load(ObjectManager $manager): void
    {
        $status = [
            EventStatus::ACTIVE,
            EventStatus::INACTIVE
        ];

        foreach ($status as $name){

            $eventStatus = EventStatus::make($name);
            $this->eventStatusRepository->persist($eventStatus);

            $this->addReference($name, $eventStatus);
        }

        $this->eventStatusRepository->flush();
    }
}
