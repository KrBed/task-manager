<?php

// src/DTO/TaskStatusUpdateDTO.php
namespace App\DTO;

class TaskStatusUpdateDTO
{
    private string $status;

    public function __construct(array $data)
    {
        $this->status = $data['status'] ?? '';
    }

    public function getStatus(): string
    {
        return $this->status;
    }

}


