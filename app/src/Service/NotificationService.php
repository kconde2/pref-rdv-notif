<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NotificationService
{

    public $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send($recipients = [], $message = "")
    {
        foreach ($recipients as $recipient) {
            $email = (new Email())
                ->from('no-reply@pref-rdv-notif.com')
                ->to($recipient)
                ->priority(Email::PRIORITY_HIGH)
                ->subject('Notification de rendez-vous')
                ->html($message)
                ->attachFromPath('screen1.png', 'RDV Page 1')
                ->attachFromPath('screen2.png', 'RDV Page 2')
                ->attachFromPath('screen3.png', 'RDV Page 3');

            $this->mailer->send($email);
        }
    }
}
