<?php

namespace App\Application\User\Command;

class VerifiedEmailCommand
{
    public function __construct(
        private string $token
    ){}

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }
}