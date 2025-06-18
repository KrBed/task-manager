<?php

// src/Service/TaskService.php
namespace App\Service;

use App\Enum\TaskStatus;
use App\Repository\TaskRepository;
use Symfony\Bundle\SecurityBundle\Security;


class TaskService
{
    private TaskRepository $taskRepository;
    private Security $security;

    public function __construct(TaskRepository $taskRepository,  Security $security)
    {
        $this->taskRepository = $taskRepository;
        $this->security = $security;
    }

    public function getSortedTasks(): array
    {
        $user = $this->security->getUser();

        if (!$user) {
            throw new \RuntimeException('Nie znaleziono zalogowanego użytkownika.');
        }

        $tasks = $this->taskRepository->findBy(['user' => $user]);

        $sortedTasks = [
            TaskStatus::PENDING->value => [],
            TaskStatus::DONE->value => [],
            TaskStatus::REJECTED ->value=> [],
        ];

        foreach ($tasks as $task) {
            $sortedTasks[$task->getStatus()][] = $task;
        }

        return $sortedTasks;
    }
}