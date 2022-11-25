<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Sender
{
    private $mailer;
    public function __construct(MailerInterface $mailer){

        $this->mailer = $mailer;
    }

    public function sendMessage(User $user, User $friend, $subject, $body){


        $email = (new Email())
            ->from($user->getEmail())
            ->to($friend->getEmail())
            ->subject($subject)
            ->html($body);

        $this->mailer->send($email);
        file_put_contents('debug.txt', $email);
    }
}