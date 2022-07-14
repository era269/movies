<?php

declare(strict_types=1);

namespace App\Listener;

use App\Domain\Message\MovieAddedEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

final class UserMovieNotificationEventListener
{
    private MailerInterface $mailer;
    private Security $security;
    private SerializerInterface $serializer;

    public function __construct(
        MailerInterface $mailer,
        Security        $security,
        SerializerInterface $serializer
    )
    {
        $this->mailer = $mailer;
        $this->security = $security;
        $this->serializer = $serializer;
    }

    //ToDo: integrate with messenger to do asynchronously
    public function __invoke(MovieAddedEvent $event)
    {
        $address = $this->security->getUser()->getUserIdentifier();
        $subject = sprintf('Movie "%s" added', $event->getName());
        $email = (new Email())
            ->from('movies@gmail.com')
            ->to($address)
            ->subject($subject)
            ->text(
                $this->serializer->serialize($event, JsonEncoder::FORMAT)
            );

        $this->mailer
            ->send($email);
    }
}
