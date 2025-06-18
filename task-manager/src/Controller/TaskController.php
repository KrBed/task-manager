<?php

namespace App\Controller;

use App\DTO\TaskStatusUpdateDTO;
use App\Entity\Task;
use App\Enum\TaskStatus;
use App\Form\TaskFormType;
use App\Repository\TaskRepository;
use App\Service\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/task')]
class TaskController extends AbstractController
{
    #[Route('/list', name: 'task_list')]
    public function index( TaskRepository $taskRepository)
    {
        $tasks = $taskRepository->findAll();

        return $this->render('task/index.html.twig',['tasks' => $tasks]);
    }
    #[Route('/show/{uuid}', name: 'task_show', methods: ['GET'])]
    public function show(string $uuid, TaskRepository $taskRepository): Response
    {
        $task = $taskRepository->find($uuid);

        if (!$task) {
            $this->addFlash('error', 'Zadanie nie znalezione.');
            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }
    #[Route('/edit/{uuid}', name: 'task_edit')]
    public function edit(
        string $uuid,
        TaskRepository $taskRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $task = $taskRepository->find($uuid);

        if (!$task) {
            $this->addFlash('error', 'Zadanie nie znalezione.');
            return $this->redirectToRoute('task_list');
        }

        $form = $this->createForm(TaskFormType::class, $task,['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Zadanie zaktualizowane.');
            return $this->redirectToRoute('task_show', ['uuid' => $task->getUuid()]);
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    #[Route('/create', name: 'task_create')]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $task = new Task();
        $task->setStatus(TaskStatus::PENDING->value);
        $token = null;

        $session = $request->getSession();

        if (!$session->has('form_token')) {
            $token = Uuid::uuid4()->toString();
            $session->set('form_token', $token);
        } else {
            $token = $session->get('form_token');
        }

        $form = $this->createForm(TaskFormType::class, $task, [
            'anti_duplicate_token' => $token,
            'is_edit' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $submittedToken = $form->get('anti_duplicate_token')->getData();
            $sessionToken = $session->get('form_token');

            if ($submittedToken !== $sessionToken) {
                $this->addFlash('error', 'Formularz został już przetworzony lub token jest nieprawidłowy.');
                return $this->redirectToRoute('task_create');
            }

            $session->remove('form_token');

            $user = $this->getUser();
            $task->setUser($user);

            $entityManager->persist($task);
            $entityManager->flush();
            $this->addFlash('success', 'Zadanie dodane.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/swimlines', name: 'task_swimlines')]
    public function swimlines(TaskService $taskService): Response
    {
        $sortedTasks = $taskService->getSortedTasks();

        return $this->render('task/swimlines.html.twig', [
            'tasks' => $sortedTasks,
            'statuses' => TaskStatus::cases(),
        ]);
    }
    #[Route('/update-status/{uuid}', name: 'task_update_status', methods: ['POST'])]
    public function updateStatus(
        string $uuid,
        Request $request,
        TaskRepository $taskRepository,
        EntityManagerInterface $entityManager
    ): Response {

        $task = $taskRepository->find($uuid);

        if (!$task) {
            return $this->json(['error' => 'Task not found'], Response::HTTP_NOT_FOUND);
        }

        $dto = new TaskStatusUpdateDTO($request->toArray());

        if (!TaskStatus::isValidStatus($dto->getStatus())) {
            return $this->json(['error' => 'Invalid status'], Response::HTTP_BAD_REQUEST);
        }

        $task->setStatus($dto->getStatus());
        $entityManager->flush();

        return $this->json(['success' => true], Response::HTTP_OK);
    }
}