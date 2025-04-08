<?php

namespace App\Application\User\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\EmailService;
use App\Service\UserService;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

class RegisterUserHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $hasher,
        private EmailService $emailService,
        private UserService $userService
    )
    {
    }

    /**
     * @param RegisterUserCommand $command
     * @return void
     * @throws \Exception
     */
    public function execute(RegisterUserCommand $command): void
    {
        $user = $this->userService->userOfEmail($command->getEmail());
        if($user !== null ){
            throw new \Exception("User already exists.");
        }

        $user = new User();
        $user->setEmail($command->getEmail());
        $password = $this->hasher->hashPassword($user,$command->getPassword());
        $user->setPassword($password);
        $user->setVerified(false);
        $user->setUserToken(Uuid::v4());
        $user->setRoles([User::ROLE_USER]);
        $user->setTokenExpiration(new \DateTime());

        $this->userRepository->save($user);

        if($user->getUserToken() != null){
            $this->emailService->sendRegistrationEmail($user->getEmail(),$user->getUserToken());
        }
    }
}
