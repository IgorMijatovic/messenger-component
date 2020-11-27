<?php


namespace App;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class UserNotifierService
{

    private MailerInterface $mailer;
    private Environment $twig;
    private EntityManagerInterface $em;

    public function __construct(
        MailerInterface $mailer,
        Environment $twig,
        EntityManagerInterface $em
    ) {

        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->em = $em;
    }

    public function notify(int $userId)
    {
        throw new \Exception('blobl blobl');
        $user = $this->em->find(User::class, $userId);
        $email = (new Email())
            ->from('noreply@site.fr')
            ->to($user->getEmail())
            ->html($this->twig->render('email/notification.html.twig', ['user' => $user]));
        sleep(2);
        $this->mailer->send($email);
    }

}
