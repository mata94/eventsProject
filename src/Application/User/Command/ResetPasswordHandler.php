<?php

namespace App\Application\User\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetPasswordHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $hasher,
    ){}

    /**
     * @param ResetPasswordCommand $command
     * @return void
     * @throws \Exception
     */
    public function execute(ResetPasswordCommand $command): void
    {
        /** @var User $user */
        $user = $this->userRepository->findOneByToken($command->getToken());

        if($user == null){
            throw new \Exception("User not found.");
        }

        $currentTime = new \DateTime();

        if($user->getTokenExpiration() == null){
            throw new \Exception("Time expired.");
        }

        $time = $currentTime->diff($user->getTokenExpiration());

        if($time->i > 60){
            throw new \Exception("Time expired.");
        }

        if($command->getPassword1() != $command->getPassword2()){
            throw new \Exception("Password does not  match.");
        }

        $password = $command->getPassword1();
        $user->setPassword($this->hasher->hashPassword($user,$password));
        $user->setTokenExpiration(null);
        $user->setVerified(true);

        $this->userRepository->save($user);
    }
}