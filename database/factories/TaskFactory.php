<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(), // ダミーのProject ID
            'task_name' => $this->faker->sentence(mt_rand(3, 6)),
            'assigned_project_member_id' => null, // nullableなのでデフォルトはnull
            'status' => $this->faker->randomElement(['Pending', 'In Progress', 'Completed', 'Blocked']),
            'priority' => $this->faker->randomElement(['Low', 'Medium', 'High']),
            'due_date_start' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'due_date_end' => $this->faker->dateTimeBetween('+1 month', '+3 months')->format('Y-m-d'),
        ];
    }
}