<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Recipe;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_comment()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();

        $response = $this->postJson('/comments', [
            'content' => 'This is a test comment.',
            'recipe_id' => $recipe->id,
            'user_id' => $user->id,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Comment Created Successfully'
            ]);

        $this->assertDatabaseHas('comments', [
            'content' => 'This is a test comment.',
            'recipe_id' => $recipe->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_can_show_comment()
    {
        $comment = Comment::factory()->create();

        $response = $this->getJson("/comments/{$comment->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'comment' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'recipe_id' => $comment->recipe_id,
                    'user_id' => $comment->user_id,
                ]
            ]);
    }

    public function test_can_update_comment()
    {
        // Buat dummy data untuk user, recipe, dan comment
        $user = \App\Models\User::factory()->create();
        $recipe = \App\Models\Recipe::factory()->create();
        $comment = \App\Models\Comment::factory()->create([
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
        ]);

        // Lakukan request update dengan menyertakan user_id dan recipe_id
        $response = $this->putJson("/comments/{$comment->id}", [
            'content' => 'Updated comment content.',
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Comment Updated Successfully'
            ]);

        // Verifikasi bahwa komentar benar-benar terupdate di database
        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'content' => 'Updated comment content.',
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
        ]);
    }

    public function test_can_delete_comment()
    {
        $comment = Comment::factory()->create();

        $response = $this->deleteJson("/comments/{$comment->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Comment deleted successfully'
            ]);

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    }
}
