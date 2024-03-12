<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailNotificationManager
{
    public function __construct(private readonly MailerInterface $mailer)
    {
    }
    public function sendEmailAfterRegistration(User $user): void
    {
        $email = (new TemplatedEmail())
            ->from('noreply@thegoodcard.fr')
            ->to($user->getEmail())
            ->subject('Bienvenue sur The Good Card')
            ->htmlTemplate('email/welcome.html.twig')
            ->context([
                'user' => $user,
            ]);

        $this->mailer->send($email);
    }
}
