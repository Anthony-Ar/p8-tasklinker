<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TaskController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {}

    #[Route('/project/{id}/add-task', name: 'app_add_task')]
    public function addTask(Request $request, int $id): Response
    {
        $project = $this->entityManager->getRepository(Project::class)->find($id);

        if (!$project || !$project->isActive()) {
            return $this->redirectToRoute('app_show_projects');
        }

        $task = new Task();
        $task->setProject($project);

        $form = $this->createForm(TaskType::class, $task, ['project' => $project]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($task);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_show_project', ['id' => $project->getId()]);
        }

        return $this->render('pages/task/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/task/edit/{id}', name: 'app_edit_task')]
    public function editTask(Request $request, int $id): Response
    {
        $task = $this->entityManager->getRepository(Task::class)->find($id);

        if (!$task) {
            return $this->redirectToRoute('app_show_projects');
        }

        $project = $task->getProject();

        $form = $this->createForm(TaskType::class, $task, ['project' => $project]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($task);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_show_project', ['id' => $project->getId()]);
        }

        return $this->render('pages/task/edit.html.twig', [
            'task' => $task,
            'form' => $form->createView()
        ]);
    }
}
