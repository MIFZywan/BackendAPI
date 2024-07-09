<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_category()
    {
        $response = $this->postJson('/categories', [
            'name' => 'New Category',
            'description' => 'Description of new category',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Category Created Successfully',
            ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'New Category',
            'description' => 'Description of new category',
        ]);
    }

    public function test_can_show_category()
    {
        $category = Category::factory()->create();

        $response = $this->getJson("/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description,
                ],
            ]);
    }

    public function test_can_update_category()
    {
        $category = Category::factory()->create();

        $response = $this->putJson("/categories/{$category->id}", [
            'name' => 'Updated Category',
            'description' => 'Updated description of category',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Category Updated Successfully',
            ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Category',
            'description' => 'Updated description of category',
        ]);
    }

    public function test_can_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson("/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Category deleted successfully',
            ]);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
}
