<?php

namespace App\Application\User\Command;

use App\Entity\User;
use App\Repository\UserRepository;

class VerifiedEmailHandler
{
    public function __construct(
        private UserRepository $userRepository
    ){}

    /**
     * @param VerifiedEmailCommand $command
     * @return void
     * @throws \Exception
     */
    public function execute(VerifiedEmailCommand $command): void
    {
        /** @var User $user */
        $user = $this->userRepository->findOneByToken($command->getToken());

        if($user == null){
            throw new \Exception("User not found");
        }

        if($user->getTokenExpiration() == null){
            throw new \Exception("The verification has expired.");
        }

        $now = new \DateTime();
        $time = $now->diff($user->getTokenExpiration());

        if($time->i > 60){
            throw new \Exception("The verification has expired.");
        }

        $user->setVerified(true);
        $user->setTokenExpiration(null);
        $this->userRepository->save($user);
    }
}