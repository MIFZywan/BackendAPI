<?php

namespace Tests\Feature;

use App\Models\Recipe;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_recipe()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->postJson('/recipes', [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'New Recipe',
            'description' => 'Description of new recipe',
            'ingredients' => 'List of ingredients',
            'steps' => 'Steps to prepare',
            'image' => 'image.jpg',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Recipe Created Successfully',
            ]);

        $this->assertDatabaseHas('recipes', [
            'title' => 'New Recipe',
            'description' => 'Description of new recipe',
        ]);
    }

    public function test_can_show_recipe()
    {
        $recipe = Recipe::factory()->create();

        $response = $this->getJson("/recipes/{$recipe->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'recipe' => [
                    'id' => $recipe->id,
                    'title' => $recipe->title,
                    'description' => $recipe->description,
                ],
            ]);
    }

    public function test_can_update_recipe()
    {
        $recipe = Recipe::factory()->create();

        $response = $this->putJson("/recipes/{$recipe->id}", [
            'user_id' => $recipe->user_id,  // Pastikan untuk menyertakan user_id
            'category_id' => $recipe->category_id,  // Pastikan untuk menyertakan category_id
            'title' => 'Updated Recipe',
            'description' => 'Updated description of recipe',
            'ingredients' => 'Updated ingredients',
            'steps' => 'Updated steps',
            'image' => 'updated_image.jpg',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Recipe Updated Successfully',
            ]);

        $this->assertDatabaseHas('recipes', [
            'id' => $recipe->id,
            'title' => 'Updated Recipe',
            'description' => 'Updated description of recipe',
        ]);
    }

    public function test_can_delete_recipe()
    {
        $recipe = Recipe::factory()->create();

        $response = $this->deleteJson("/recipes/{$recipe->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Recipe deleted successfully',
            ]);

        $this->assertDatabaseMissing('recipes', [
            'id' => $recipe->id,
        ]);
    }

    public function test_list_recipes()
    {
        $recipes = Recipe::factory()->count(3)->create();

        $response = $this->getJson('/recipes');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'recipes' => $recipes->toArray(),
            ]);
    }
}
