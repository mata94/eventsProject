<?php

namespace App\DataFixtures;

use App\Entity\SlotUser;
use App\Repository\SlotUserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SlotUserFixtures extends Fixture implements DependentFixtureInterface
{
    public const SLOT_USER1 = "SLOT USER 1";
    public const SLOT_USER2 = "SLOT USER 2";
    public const SLOT_USER3 = "SLOT USER 3";

    public function __construct(
        private SlotUserRepository $slotUserRepository
    ){}

    public function load(ObjectManager $manager): void
    {
        $slotUser = [
            self::SLOT_USER1,
            self::SLOT_USER2
        ];

        $slots = [
            $this->getReference(SlotFixtures::SLOT1),
            $this->getReference(SlotFixtures::SLOT2)
        ];

        $users = [
            $this->getReference(UserFixtures::ADMIN),
            $this->getReference(UserFixtures::USER)
        ];

        for($i=0; $i< count($slotUser); $i++) {
            $slotUserEntity = SlotUser::make($users[$i],$slots[$i]);
            $this->slotUserRepository->persist($slotUserEntity);
            $this->addReference($slotUser[$i],$slotUserEntity);
        }
        $this->slotUserRepository->flush();
    }

    public function getDependencies()
    {
        return [
          UserFixtures::class,
          SlotFixtures::class
        ];
    }
}
