<?php

namespace App\DataFixtures;

use App\Entity\Slot;
use App\Repository\SlotRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SlotFixtures extends Fixture implements DependentFixtureInterface
{
    public const SLOT1 = "SLOT 1";
    public const SLOT2 = "SLOT 2";
    public const SLOT3 = "SLOT 3";

    public function __construct(
        private SlotRepository $slotRepository
    ){}

    public function load(ObjectManager $manager): void
    {
        $slots = [
            self::SLOT1,
            self::SLOT2
        ];

        $events = [
            $this->getReference(EventFixtures::EVENT1),
            $this->getReference(EventFixtures::EVENT2),
            $this->getReference(EventFixtures::EVENT3),
        ];

        for($i=0; $i< count($slots); $i++)
        {
            $startTime = new \DateTime("2024-10-01 09:00:00");
            $endTime = (clone $startTime)->modify('+2 hours');
            $slot = Slot::make($events[$i],$i+10,$startTime,$endTime);

            $this->slotRepository->persist($slot);
            $this->addReference($slots[$i],$slot);
        }

        $this->slotRepository->flush();
    }

    public function getDependencies()
    {
        return[
            EventFixtures::class
        ];
    }
}
