<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/', name: 'task_index')]
    public function index()
    {
        // This method will handle the logic for displaying tasks
        return $this->render('task/index.html.twig');
    }

    public function create()
    {
        // This method will handle the logic for creating a new task
        return $this->render('task/create.html.twig');
    }

    public function edit($id)
    {
        // This method will handle the logic for editing an existing task
        return $this->render('task/edit.html.twig', ['id' => $id]);
    }
}