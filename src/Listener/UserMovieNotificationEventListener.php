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
    public function __construct(private readonly MailerInterface $mailer, private readonly Security        $security, private readonly SerializerInterface $serializer)
    {
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
