<?php

// src/DataFixtures/TaskFixtures.php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create();
        $tasks = [];
        $users = $this->userRepository->findAll();

        if (empty($users)) {
            throw new \RuntimeException('Brak użytkowników w bazie danych. Dodaj użytkowników przed uruchomieniem tej klasy.');
        }

        // Tworzenie 10 przykładowych zadań
        for ($i = 0; $i < 20; $i++) {
            $task = new Task();
            $task->setTitle($faker->sentence);
            $task->setDescription($faker->paragraph);
            $task->setDeadline($faker->dateTimeBetween('now', '+1 month'));
            $task->setStatus($faker->randomElement(['pending', 'completed', 'rejected']));

            if ($i > 0) {
                // Wybierz losowy task jako rodzica z już utworzonych
                $parentTask = $tasks[array_rand($tasks)];
                $task->setParent($parentTask);
            }

            // Losowo przypisz użytkownika do zadania
            $randomUser = $users[array_rand($users)];
            $task->setUser($randomUser);

            $manager->persist($task);
            $tasks[] = $task;
        }

        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}

