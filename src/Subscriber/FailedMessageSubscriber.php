<?php

namespace App\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;
use Symfony\Component\Mime\Email;

class FailedMessageSubscriber implements EventSubscriberInterface
{

    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer) {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            WorkerMessageFailedEvent::class => 'onMessageFailed'
        ];
    }

    public function onMessageFailed(WorkerMessageFailedEvent $event)
    {
        $message = get_class($event->getEnvelope()->getMessage());
        $trace = $event->getThrowable()->getTraceAsString();
        $email = (new Email())
            ->from('noreply@server.fr')
            ->to('administrator@server.fr')
            ->text(<<<TEXT
Une erreur est survenue lors du traitement d'une tache

{$message}

{$trace}
TEXT);
        $this->mailer->send($email);
    }

}
