<?php

namespace App\Application\SlotUser\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\EmailService;
use App\Service\SlotService;
use App\Service\SlotUserService;
use App\Service\UserService;

class SlotConfirmArrivalHandler
{
    public function __construct(
        private SlotUserService $slotUserService,
        private UserRepository $userRepository,
        private SlotService $slotService,
        private UserService $userService
    ){}

    /**
     * @param SlotConfirmArrivalCommand $command
     * @return void
     * @throws \Exception
     */
    public function execute(SlotConfirmArrivalCommand $command): void
    {
        $user = $this->userService->userOfEmail($command->getEmail());

        if($user === null){
            $user = new User();
            $user->setFullName($command->getFullName());
            $user->setEmail($command->getEmail());
            $user->setRoles(["ROLE_GUEST"]);
            $user->setVerified(true);
            $this->userRepository->save($user);
        }

        $slot = $this->slotService->getSlotById($command->getSlotId());
        if($slot->isSlotFull()){
            throw new \Exception("Slot is full.");
        }

        $slotUser = $this->slotUserService->getSlotUserBySlotAndUser($slot,$user);
        if($slotUser === null){
            $this->slotUserService->createSlotUser(
                $slot,
                $user
            );
        }else{
            throw new \Exception("You have already subscribed.");
        }
    }
}
