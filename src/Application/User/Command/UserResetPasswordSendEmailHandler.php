<?php

namespace App\Application\User\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\EmailService;
use App\Service\UserService;
use Symfony\Component\Uid\Uuid;

class UserResetPasswordSendEmailHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private EmailService $emailService,
    )
    {}

    /**
     * @param UserResetPasswordSendEmailCommand $command
     * @return void
     * @throws \Exception
     */
    public function execute(UserResetPasswordSendEmailCommand $command): void
    {
        /** @var User $user */
        $user = $this->userRepository->findOneByEmail($command->getEmail());

        if($user == null){
            throw new \Exception("User not found");
        }

        $user->setUserToken(Uuid::v4());
        $user->setTokenExpiration(new \DateTime());
        $this->userRepository->save($user);

        if($user->getUserToken() != null){
            $this->emailService->sendResetPasswordEmail($user->getEmail(),$user->getUserToken());
        }
    }
}