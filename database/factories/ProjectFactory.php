<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(3);
        return [
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title),
            'short_description' => $this->faker->text(150),
            'full_description' => $this->faker->paragraphs(3, true),
            'client_name' => $this->faker->company,
            'project_url' => $this->faker->url,
            'completion_date' => $this->faker->date(),
            'sort_order' => $this->faker->numberBetween(0, 10),
            'status' => true,
        ];
    }
}
