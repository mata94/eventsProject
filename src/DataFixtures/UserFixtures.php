<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    public const PASSWORD = "admin123";
    public const EMAIL = "admin@gmail.com";
    public const ADMIN = "admin";

    public const PASSWORD_USER = "user123";
    public const EMAIL_USER = "user@gmail.com";
    public const USER = "user";

    public function __construct(
        private UserPasswordHasherInterface $hasher,
        private UserRepository $userRepository
    ){}

    public function load(ObjectManager $manager): void
    {
        $admin = User::make(self::EMAIL,
            [User::ROLE_ADMIN,User::ROLE_USER],
        );
        $admin->setPassword($this->hasher->hashPassword($admin,self::PASSWORD));
        $this->userRepository->save($admin);
        $this->addReference(self::ADMIN,$admin);

        $user = User::make(self::EMAIL_USER,
            [User::ROLE_USER]
        );
        $user->setPassword($this->hasher->hashPassword($user,self::PASSWORD_USER));
        $this->userRepository->save($user);
        $this->addReference(self::USER,$user);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['user'];
    }


}
