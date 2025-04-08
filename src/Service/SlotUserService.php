<?php

namespace App\Service;

use App\Entity\Slot;
use App\Entity\SlotUser;
use App\Entity\User;
use App\Helper\DateTimeHelper;
use App\Repository\SlotUserRepository;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Uid\Uuid;

class SlotUserService
{
    public function __construct(
        private SlotUserRepository $slotUserRepository,
        private EmailService $emailService,
        private QrCodeService $qrCodeService
    ){}

    /**
     * @param Slot $slot
     * @param User $user
     * @return void
     */
    public function createSlotUser(Slot $slot,User $user): void
    {
        $uuid = Uuid::v4();
        $qrCode = $this->qrCodeService->generateQrCode((string)$uuid);

        $slotUser = new SlotUser();
        $slotUser->setUser($user);
        $slotUser->setSlot($slot);
        $slotUser->setQrCode($uuid);
        $this->slotUserRepository->save($slotUser);

        $this->emailService->sendConfirmArrivalMessage($user,$slot,$slotUser,$qrCode);
    }

    /**
     * @param Slot $slot
     * @param User $user
     * @return void
     */
    public function createSlotUserForAdmin(Slot $slot,User $user): void
    {
        if(!$slot->isSlotFull() && !$slot->isFinished()){
            $uuid = Uuid::v4();
            $qrCode = $this->qrCodeService->generateQrCode((string)$uuid);

            $slotUser = new SlotUser();
            $slotUser->setUser($user);
            $slotUser->setSlot($slot);
            $slotUser->setQrCode($uuid);
            $this->slotUserRepository->save($slotUser);

            $this->emailService->sendConfirmArrivalMessage($user,$slot,$slotUser,$qrCode);
        }else{
            throw new \Exception("Slot is not available.");
        }
    }

    /**
     * @param Slot $slot
     * @param User $user
     * @return SlotUser|null
     */
    public function getSlotUserBySlotAndUser(Slot $slot,User $user): ?SlotUser
    {
        return $this->slotUserRepository->findBySlotAndUser($slot,$user);
    }

    /**
     * @param int $id
     * @return SlotUser
     */
    public function getSlotUserById(int $id): SlotUser
    {
        /** @var SlotUser $slotUser */
        $slotUser = $this->slotUserRepository->findById($id);
        if($slotUser === null){
            throw new \Exception("Slot user not found");
        }

        return $slotUser;
    }

    /**
     * @param string $code
     * @return SlotUser
     * @throws \Exception
     */
    public function getSlotUserByQrCOde(string $code): SlotUser
    {
        /** @var SlotUser $slotUser */
        $slotUser = $this->slotUserRepository->findByQrCode($code);
        if($slotUser === null){
            throw new \Exception("Slot user not found");
        }

        return $slotUser;
    }

    /**
     * @param string $code
     * @return SlotUser
     * @throws \Exception
     */
    public function check(SlotUser $slotUser): SlotUser
    {
        if($slotUser->getScanTime() === null){
            $date = DateTimeHelper::getNewForSlot("now",'UTC',2);
            $slotUser->setScanTime($date);
            $this->slotUserRepository->flush();
        }

        return $slotUser;
    }
}