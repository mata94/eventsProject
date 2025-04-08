<?php

namespace App\Service\imp;

use App\Service\EmailSenderInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use \Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class GmailService implements EmailSenderInterface
{
    private Mailer $mailer;

    public function __construct(
    )
    {
        $this->make();
    }

    public function make(): void
    {
        $transport = Transport::fromDsn($_ENV["MAILER_DSN"]);
        $this->mailer = new Mailer($transport);
    }

    /**
     * @param Email $email
     * @return void
     * @throws TransportExceptionInterface
     */
    public function send(Email $email): void
    {
        $this->mailer->send($email);
    }
}