<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class ProjectController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {}

    #[Route('/', name: 'app_show_projects')]
    public function showProjects(): Response
    {
        return $this->render('pages/project/show.html.twig', [
            'projects' => $this->entityManager->getRepository(Project::class)->findAll(),
        ]);
    }
}
