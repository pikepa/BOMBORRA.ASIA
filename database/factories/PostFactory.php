<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = $this->faker->sentence;
        $slug = Str::slug($title,'-');

        return [
            'uuid'              => $this->faker->uuid,
            'cover_image'       => $this->faker->imageUrl(360, 360, 'animals', true, 'dogs', true, 'jpg'),
            'title'             => $title,
            'slug'              => $slug,
            'body'              => $this->faker->sentence(20),
            'meta_description'  => $this->faker->paragraph,
            'published_at'      => $this->faker->dateTimeThisMonth(),
            'featured'          => $this->faker->numberBetween(0,1),
            'author_id'         => User::factory()->create(),
            'category_id'       => Category::factory()->create(),
        ];
    }
}
