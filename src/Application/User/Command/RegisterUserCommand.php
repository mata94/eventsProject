<?php

namespace App\Application\User\Command;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterUserCommand
{
    #[Assert\Email]
    #[Assert\NotBlank]
    private string $email;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 6,
        max: 50,
        minMessage: 'Your password must be at least {{ limit }} characters long',
        maxMessage: 'Your password cannot be longer than {{ limit }} characters',
    )]
    private string $password;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}