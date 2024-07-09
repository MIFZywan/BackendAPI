<?php

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecipeFactory extends Factory
{
    protected $model = Recipe::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'ingredients' => $this->faker->paragraph,
            'steps' => $this->faker->paragraph,
            'image' => $this->faker->imageUrl(),
            'user_id' => User::factory(),  
            'category_id' => Category::factory(), 
        ];
    }
}
