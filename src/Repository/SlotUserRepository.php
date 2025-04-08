<?php

namespace App\Repository;

use App\Entity\Slot;
use App\Entity\SlotUser;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class SlotUserRepository extends BaseRepository
{
   protected function getEntityName()
   {
       return SlotUser::class;
   }

    /**
     * @param Slot $slot
     * @param User $user
     * @return SlotUser|null
     */
   public function findBySlotAndUser(Slot $slot,User $user): ?SlotUser
   {
       return $this->findOneBy([
           "slot" => $slot,
           "user" => $user
       ]);
   }

    /**
     * @param string $qrCode
     * @return SlotUser|null
     */
   public function findByQrCode(string $qrCode):  ?SlotUser
   {
       return $this->findOneBy([
           "qrCode" => $qrCode,
       ]);
   }
}
