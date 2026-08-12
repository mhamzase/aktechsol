<?php

namespace Database\Factories;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlogPostFactory extends Factory
{
    protected $model = BlogPost::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(4);
        return [
            'category_id' => BlogCategory::inRandomOrder()->first()?->id,
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title),
            'excerpt' => $this->faker->text(150),
            'content' => $this->faker->paragraphs(5, true),
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'sort_order' => $this->faker->numberBetween(0, 10),
            'status' => true,
        ];
    }
}
