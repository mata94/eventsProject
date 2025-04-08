<?php

namespace App\Application\User\Command;

use Symfony\Component\Validator\Constraints as Assert;

class UserResetPasswordSendEmailCommand
{
    /**
     * @Serializer\Type("string")
     */
    #[Assert\NotBlank]
    private string $email;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}