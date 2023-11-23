<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->words(5,true) ;
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'body' => fake()->paragraphs(50,true),
            'image_url' => "https://picsum.photos/seed/lion".rand(1, 500)."/400/210",
            'author_id'=> User::all()->random()->id
        ];
    }
}
