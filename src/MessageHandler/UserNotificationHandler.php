<?php

namespace App\MessageHandler;

use App\Entity\User;
use App\Message\UserNotificationMessage;
use App\UserNotifierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UserNotificationHandler implements MessageHandlerInterface
{

    private EntityManagerInterface $em;
    private UserNotifierService $notifierService;

    public function __construct(
        EntityManagerInterface $em,
        UserNotifierService $notifierService
    ) {
        $this->em = $em;
        $this->notifierService = $notifierService;
    }

    public function __invoke(UserNotificationMessage $message)
    {
        $user = $this->em->find(User::class, $message->getUserId());
        if ($user !== null) {
            $this->notifierService->notify($user);
        }
    }

}
