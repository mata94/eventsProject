<?php

namespace App\Service;

use App\Entity\Slot;
use App\Entity\SlotUser;
use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use \Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Part\DataPart;
use Twig\Environment;

class EmailService
{
    public const EMAIL = "testiranjeEmail94@gmail.com";

    public function __construct(
        private MailerInterface $mailer,
        private Environment $twig,
    )
    {}

    /**
     * @param string $emailTo
     * @param string $token
     * @return void
     * @throws TransportExceptionInterface
     */
    public function sendRegistrationEmail(string $emailTo,string $token): void
    {
        $email = (new Email())
            ->from( self::EMAIL)
            ->to($emailTo)
            ->subject('Successfully registration')
            ->html($this->twig->render('Email/registrationResponse.html.twig', [
                'emailTo' => $emailTo,
                'verificationUrl' => "https://mostarbus.hunterdev.pro/api/public/verified_email/" . $token,
            ]));

        $this->mailer->send($email);
    }

    /**
     * @param string $emailTo
     * @param string $token
     * @return void
     * @throws TransportExceptionInterface
     */
    public function sendVerifyEmailMessage(string $emailTo,string $token): void
    {
        $email = (new Email())
            ->from( self::EMAIL)
            ->to($emailTo)
            ->subject('Verify email')
            ->html($this->twig->render('Email/verifyEmailMessage.html.twig', [
                'emailTo' => $emailTo,
                'verificationUrl' => "https://mostarbus.hunterdev.pro/api/public/verified_email/".$token,
            ]));

        $this->mailer->send($email);
    }

    /**
     * @param string $emailTo
     * @param string $token
     * @return void
     * @throws TransportExceptionInterface
     */
    public function sendResetPasswordEmail(string $emailTo,string $token): void
    {
        $email = (new Email())
            ->from( self::EMAIL)
            ->to($emailTo)
            ->subject('Reset password')
            ->priority(Email::PRIORITY_HIGH)
            ->html($this->twig->render("Email/resetPasswordMessage.html.twig",[
                    "emailTo" => $emailTo,
                    "verificationUrl" =>"https://mostarbus.hunterdev.pro/api/public/reset_password_form/".$token
                ]
            ));

        $this->mailer->send($email);
    }

    /**
     * @param User $user
     * @param Slot $slot
     * @param SlotUser $slotUser
     * @return void
     * @throws TransportExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendConfirmArrivalMessage(
        User $user,
        Slot $slot,
        SlotUser $slotUser,
        DataPart $qrCodeDataPart
    ): void
    {
        $email = (new Email())
            ->from( self::EMAIL)
            ->to($user->getEmail())
            ->subject('Event')
            ->priority(Email::PRIORITY_HIGH)
            ->html($this->twig->render("Email/confirmationArrivalMessage.html.twig",[
                    "user" => $user,
                    "slot" => $slot,
                    "slotUser" => $slotUser,
                    'qrCodeCid' => 'cid:' . $qrCodeDataPart->getFilename(),
                ]
            ))
            ->addPart($qrCodeDataPart);

        $this->mailer->send($email);
    }
}