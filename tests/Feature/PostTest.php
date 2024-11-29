<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;

class PostTest extends TestCase
{
    use RefreshDatabase;
 
    // 未ログインのユーザーは投稿一覧ページにアクセスできない
    public function test_guest_cannot_access_posts_index()
    {
        $response = $this->get(route('posts.index'));

        $response->assertRedirect(route('login'));
    }

    // ログイン済みのユーザーは投稿一覧ページにアクセスできる
    public function test_user_can_access_posts_index()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('posts.index'));

        $response->assertStatus(200);
        $response->assertSee($post->title);
    }

        // 未ログインのユーザーは投稿詳細ページにアクセスできない
        public function test_guest_cannot_access_posts_show()
        {
            $user = User::factory()->create();
            $post = Post::factory()->create(['user_id' => $user->id]);
    
            $response = $this->get(route('posts.show', $post));
    
            $response->assertRedirect(route('login'));
        }
    
        // ログイン済みのユーザーは投稿詳細ページにアクセスできる
        public function test_user_can_access_posts_show()
        {
            $user = User::factory()->create();
            $post = Post::factory()->create(['user_id' => $user->id]);
    
            $response = $this->actingAs($user)->get(route('posts.show', $post));
    
            $response->assertStatus(200);
            $response->assertSee($post->title);
        }
}
