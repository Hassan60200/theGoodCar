<?php

namespace App\Services;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailNotificationManager
{
    public function __construct(private readonly MailerInterface $mailer, private readonly LoggerInterface $logger)
    {
    }
    const FROM = 'noreply@thegoodcard.fr';

    public function sendEmailAfterRegistration(User $user): void
    {
        $email = (new TemplatedEmail())
            ->from(self::FROM)
            ->to($user->getEmail())
            ->subject('Bienvenue sur The Good Card')
            ->htmlTemplate('email/welcome.html.twig')
            ->context([
                'user' => $user,
            ]);

        $this->mailer->send($email);
    }

    public function sendEmailResetPassword(User $user): void
    {
        try {
            $email = (new TemplatedEmail())
                ->from(self::FROM)
                ->to($user->getEmail())
                ->subject('Réinitialisation de votre mot de passe')
                ->htmlTemplate('email/reset_password.html.twig')
                ->context([
                    'user' => $user,
                ]);

            $this->mailer->send($email);
            $this->logger->info('Email envoyé pour la réinitialisation du mot de passe');
        } catch (\Exception $e) {
            throw new \Exception('Erreur lors de l\'envoi de l\'email'.$e->getMessage());
        }
    }

    public function verifyEmail(User $user): void
    {
        $email = (new TemplatedEmail())
            ->from(self::FROM)
            ->to($user->getEmail())
            ->subject('Vérification de votre adresse email')
            ->htmlTemplate('email/verify_email.html.twig')
            ->context([
                'user' => $user,
            ]);

        $this->mailer->send($email);
    }

    public function sendEmailAfterOrder(User $user): void
    {
        $email = (new TemplatedEmail())
            ->from(self::FROM)
            ->to($user->getEmail())
            ->subject('Confirmation de votre commande')
            ->htmlTemplate('email/order_confirmation.html.twig')
            ->context([
                'user' => $user,
            ]);

        $this->mailer->send($email);
    }
}
