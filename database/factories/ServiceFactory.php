<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(3);
        return [
            'category_id' => ServiceCategory::inRandomOrder()->first()?->id,
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title),
            'short_description' => $this->faker->text(150),
            'full_description' => $this->faker->paragraphs(3, true),
            'icon' => null,
            'sort_order' => $this->faker->numberBetween(0, 10),
            'status' => true,
        ];
    }
}
