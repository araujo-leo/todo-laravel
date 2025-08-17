<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
        'title' => $this->faker->sentence(4),
        'description' => $this->faker->paragraph(),
        'user_id' => User::factory(),
        'totalPomodori' => $this->faker->numberBetween(1, 10),
        'pomodoroValue' => $this->faker->numberBetween(1, 5),
        'completedPomodori' => $this->faker->numberBetween(0, 10),
        'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
        'taskDate' => $this->faker->dateTimeBetween('-1 week', '+1 week'),
        'dueDate' => $this->faker->dateTimeBetween('+1 day', '+2 weeks'),
        ];
    }
}
