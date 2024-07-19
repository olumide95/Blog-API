<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Carbon\Carbon;

class PostTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function test_unauthenticated_user_cannot_create_post()
    {
        $postData = [
            'title' => 'Sample Post',
            'content' => 'This is a sample post content.',
            'author' => 'John Doe',
        ];

        $response = $this->postJson('/api/posts', $postData);
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_create_post()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $postData = [
            'title' => 'Sample Post',
            'content' => 'This is a sample post content.',
            'author' => 'John Doe',
        ];

        $response = $this->postJson('/api/posts', $postData);
        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id', 'title', 'content', 'author', 'publish_at', 'created_at', 'updated_at'
                ]
            ]);
    }

    public function test_user_can_view_posts()
    {
        Post::factory(5)->create();
        Post::factory(2)->unpublished()->create();

        $response = $this->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_user_can_view_unpublished_post_after_it_is_published()
    {
        Post::factory(5)->create();
        $unpublisehdPost = Post::factory()->unpublished()->create();

        Carbon::setTestNow($unpublisehdPost->publish_at);

        $this->artisan('app:publish-scheduled-posts')
         ->assertExitCode(0);

        $response = $this->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonCount(6, 'data');
    }

    public function test_user_can_view_single_post()
    {
        $post = Post::factory()->create();

        $response = $this->getJson("/api/posts/{$post->id}");
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id', 'title', 'content', 'author', 'publish_at', 'created_at', 'updated_at'
                ]
            ]);
    }

    public function test_authenticated_user_can_update_post()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $post = Post::factory()->create();
        $postData = [
            'title' => 'Updated Post Title',
            'content' => 'Updated post content.',
            'author' => 'Jane Doe',
        ];

        $response = $this->putJson("/api/posts/{$post->id}", $postData);
        $response->assertStatus(200)
            ->assertJsonFragment($postData);
    }

    public function test_authenticated_user_can_delete_post()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $post = Post::factory()->create();

        $response = $this->deleteJson("/api/posts/{$post->id}");
        $response->assertStatus(200);
    }
}
