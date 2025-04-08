<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\EventStatus;
use App\Repository\EventRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EventFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public const EVENT1 = "Event 1";
    public const EVENT2 = "Event 2";
    public const EVENT3 = "Event 3";
    public const EVENT4 = "Event 4";

    public function __construct(
        private EventRepository $eventRepository
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $events = [
            self::EVENT1,
            self::EVENT2,
            self::EVENT3,
            self::EVENT4,
        ];

        $active = $this->getReference(EventStatus::ACTIVE);

        foreach($events as $eventName){
            $event = Event::make($eventName,$active);
            $this->eventRepository->persist($event);
            $this->addReference($eventName,$event);
            $this->eventRepository->flush();
        }

    }

    public function getDependencies()
    {
        return [
            EventStatusFixtures::class
        ];
    }

    public static function getGroups(): array
    {
        return ['status'];
    }
}
