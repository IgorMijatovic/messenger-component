<?php

namespace App\Controller;

use App\AsyncMethodService;
use App\Form\NotifyUserFormType;
use App\Message\UserNotificationMessage;
use App\Repository\FailedJobRepository;
use App\UserNotifierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(
        Request $request,
        AsyncMethodService $asyncMethodService,
        FailedJobRepository $failedJobRepository
    ): Response
    {
        $form = $this->createForm(NotifyUserFormType::class, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $asyncMethodService->async(UserNotifierService::class, 'notify', [$data['user']->getId()]);
            $this->addFlash('success', 'La notification a bien été envoyée');
            return $this->redirectToRoute('home');
        }
        return $this->render('page/index.html.twig', [
            'form' => $form->createView(),
            'jobs' => $failedJobRepository->findAll()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(int $id, FailedJobRepository $failedJobRepository) {
        $failedJobRepository->reject($id);
        $this->addFlash('success', 'Task deleted');
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/retry/{id}", name="retry", methods={"POST"})
     */
    public function retry(int $id, FailedJobRepository $failedJobRepository, MessageBusInterface $messageBus) {
        $message = $failedJobRepository->find($id)->getMessage();
        $messageBus->dispatch($message);
        $failedJobRepository->reject($id);
        $this->addFlash('success', 'Task retried');
        return $this->redirectToRoute('home');
    }
}
