<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {}

    #[Route('/user', name: 'app_users_show')]
    public function showUsers(): Response
    {
        return $this->render('pages/user/show.html.twig', [
            'users' => $this->entityManager->getRepository(User::class)->findAll(),
        ]);
    }
}
