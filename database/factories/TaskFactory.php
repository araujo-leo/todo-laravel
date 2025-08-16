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
            'total_pomodoro' => $this->faker->numberBetween(1, 10),
            'pomodoro_value' => $this->faker->numberBetween(1, 5),
            'completed_pomodoro' => $this->faker->numberBetween(0, 10),
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
        ];
    }
}
