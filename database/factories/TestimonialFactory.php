<?php

namespace Database\Factories;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestimonialFactory extends Factory
{
    protected $model = Testimonial::class;

    public function definition(): array
    {
        return [
            'client_name' => $this->faker->name,
            'client_position' => $this->faker->jobTitle,
            'company' => $this->faker->company,
            'content' => $this->faker->paragraph,
            'rating' => $this->faker->numberBetween(4, 5),
            'sort_order' => $this->faker->numberBetween(1, 100),
            'status' => true,
        ];
    }
}
