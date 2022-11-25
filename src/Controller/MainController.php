<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\MessageType;
use App\Repository\TechnologyRepository;
use App\Repository\UserRepository;
use App\Service\Sender;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(UserRepository $userRepository, TechnologyRepository $technologyRepository): Response
    {
        $allUsers = $userRepository->findAll();
        $technologies = $technologyRepository->findAll();
        $nbUsers = 0;
        foreach ($allUsers as $user) {
            if ($user != $this->getUser()) {
                $nbUsers += 1;
            }
        }

//        if ($this->getUser()->getUserIdentifier() == null){
//            $this->addFlash('warning', 'Vous devez être authentifié pour voir la liste des développeurs ');
//        }

        return $this->render('main/index.html.twig', [
            'nbUsers' => $nbUsers,
            'technologies' => $technologies,
            'devs' => $allUsers,

        ]);
    }

    #[Route('/filtre-langue/{id}', name: 'filtre_langue')]
    public function triParTechno(TechnologyRepository $technologyRepository, $id, UserRepository $userRepository): Response
    {
        $technology = $technologyRepository->find($id);
        $technologies = $technologyRepository->findAll();
        $allUsers = $userRepository->technologyFilter($technology);
        $nbUsers = 0;
        foreach ($allUsers as $user) {
            if ($user != $this->getUser()) {
                $nbUsers += 1;
            }

        }
        return $this->render('main/index.html.twig', [
            'nbUsers' => $nbUsers,
            'devs' => $allUsers,
            'technologies' => $technologies,

        ]);
    }

    #[Route('/send-message/{id}', name: 'send_message')]
    public function sendMessage($id, UserRepository $userRepository, Request $request, Sender $sender): Response
    {
        $user = $userRepository->find($id);
        $messageForm = $this->createForm(MessageType::class);
        $messageForm->handleRequest($request);

        if ($messageForm->isSubmitted() && $messageForm->isValid()) {
            $sender->sendMessage($this->getUser(), $user, $messageForm->get('sujet')->getData(), $messageForm->get('corps')->getData());
            $this->addFlash('success', 'Message envoyé !');
//            return $this->redirectToRoute('accueil');

        }

        return $this->render('main/message.html.twig', [
            'user' => $user,
            'messageForm' => $messageForm->createView(),

        ]);
    }

}
