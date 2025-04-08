<?php

namespace App\Application\User\Command;

class VerifiedEmailSendCommand
{
    public function __construct(
        private string $email
    ){}

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}