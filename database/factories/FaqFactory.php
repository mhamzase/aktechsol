<?php

namespace Database\Factories;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Factories\Factory;

class FaqFactory extends Factory
{
    protected $model = Faq::class;

    public function definition(): array
    {
        return [
            'question' => $this->faker->sentence(5) . '?',
            'answer' => $this->faker->paragraph,
            'sort_order' => $this->faker->numberBetween(0, 10),
            'status' => true,
        ];
    }
}
