<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class UserService
{
    public function __construct(
        private UserRepository $userRepository
    )
    {

    }

    public function userOfEmail(string $email): ?User
    {
        return $this->userRepository->findOneByEmail($email);
    }
}
