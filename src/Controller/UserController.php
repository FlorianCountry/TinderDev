<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/friend/{id}', name: 'add_friend')]
    public function addFriend(UserRepository $userRepository, EntityManagerInterface $entityManager, $id): Response
    {
        $user = $this->getUser();
        $friend = $userRepository->find($id);
        $user->addFriend($friend);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('accueil', [

        ]);
    }

    #[Route('/unfriend/{id}', name: 'block_friend')]
    public function removeFriend(UserRepository $userRepository, EntityManagerInterface $entityManager, $id): Response
    {
        $user = $this->getUser();
        $friend = $userRepository->find($id);
        $user->removeFriend($friend);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('accueil', [

        ]);
    }




}
