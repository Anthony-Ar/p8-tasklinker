<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Status;
use App\Form\ProjectType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/project/{id}', name: 'app_show_project', requirements: ['id' => '\d+'])]
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

    #[Route('/project/add', name: 'app_add_project')]
    public function addProject(Request $request): Response
    {
        $project = new Project();

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($project);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_show_project', ['id' => $project->getId()]);
        }

        return $this->render('pages/project/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/project/{id}/edit', name: 'app_edit_project')]
    public function editProject(Request $request, int $id): Response
    {
        $project = $this->entityManager->getRepository(Project::class)->find($id);

        if(!$project) {
            return $this->redirectToRoute('app_show_projects');
        }

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($project);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_show_project', ['id' => $project->getId()]);
        }

        return $this->render('pages/project/edit.html.twig', [
            'project' => $project,
            'form' => $form->createView()
        ]);
    }

    #[Route('/project/{id}/delete', name: 'app_delete_project')]
    public function deleteProject(int $id): Response
    {
        $project = $this->entityManager->getRepository(Project::class)->find($id);

        if($project) {
            $this->entityManager->remove($project);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_show_projects');
    }
}
