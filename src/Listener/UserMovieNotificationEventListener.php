<?php

declare(strict_types=1);

namespace App\Listener;

use App\Domain\Message\UserMovieAddedEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Security;

final class UserMovieNotificationEventListener
{
    private MailerInterface $mailer;
    private Security $security;

    public function __construct(
        MailerInterface $mailer,
        Security        $security
    )
    {
        $this->mailer = $mailer;
        $this->security = $security;
    }

    //ToDo: integrate with messenger to do asynchronously
    public function __invoke(UserMovieAddedEvent $event)
    {
        $address = $this->security->getUser()->getUserIdentifier();
        $subject = sprintf('Movie "%s" added', $event->getName());
        $email = (new Email())
            ->to($address)
            ->subject($subject);

        $this->mailer
            ->send($email);
    }
}
