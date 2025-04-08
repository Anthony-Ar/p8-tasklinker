<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Status;
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
            'projects' => $this->entityManager->getRepository(Project::class)->findBy(['active' => true]),
        ]);
    }

    #[Route('/project/{id}', name: 'app_show_project')]
    public function showProject(int $id): Response
    {
        $project = $this->entityManager->getRepository(Project::class)->find($id);

        if (!$project) {
            return $this->redirectToRoute('app_show_projects');
        }

        return $this->render('pages/project/show_detail.html.twig', [
            'status' => $this->entityManager->getRepository(Status::class)->findAll(),
            'project' => $project
        ]);
    }
}
