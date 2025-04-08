<?php

namespace App\Application\User\Command;

use Symfony\Component\Validator\Constraints as Assert;

class ResetPasswordCommand
{
    #[Assert\NotBlank]
    private string $token;

    /**
     * @Serializer\Type ("string")
     */
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 6,
        max: 50,
        minMessage: 'Your password must be at least {{ limit }} characters long',
        maxMessage: 'Your password cannot be longer than {{ limit }} characters',
    )]
    private string $password1;

    /**
     * @Serializer\Type ("string")
     */
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 6,
        max: 50,
        minMessage: 'Your password must be at least {{ limit }} characters long',
        maxMessage: 'Your password cannot be longer than {{ limit }} characters',
    )]
    private string $password2;

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getPassword1(): string
    {
        return $this->password1;
    }

    public function setPassword1(string $password1): void
    {
        $this->password1 = $password1;
    }

    public function getPassword2(): string
    {
        return $this->password2;
    }

    public function setPassword2(string $password2): void
    {
        $this->password2 = $password2;
    }
}