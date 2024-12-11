<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_posts()
    {
        $user = User::factory()->create();
        Post::factory()->count(3)->for($user)->create();

        $response = $this->actingAs($user)->get(route('posts.index'));

        $response->assertStatus(200);
        $response->assertViewIs('posts.index');
        $response->assertViewHas('posts');
    }

    public function test_show_displays_a_single_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();

        $response = $this->actingAs($user)->get(route('posts.show', $post));

        $response->assertStatus(200);
        $response->assertViewIs('posts.show');
        $response->assertViewHas('post', $post);
    }

    public function test_create_redirects_to_edit_if_post_exists()
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();

        $response = $this->actingAs($user)->get(route('posts.create'));

        $response->assertRedirect(route('posts.edit', $post->id));
        $response->assertSessionHas('info', 'すでに投稿があります。編集ページに移動しました。');
    }

    public function test_store_creates_a_new_post()
    {
        // テスト用のユーザーを作成してログイン
        $user = User::factory()->create();

        // テストデータを準備
        $data = [
            'title' => 'Test Post',
            'content' => 'This is a test post.',
            'image' => UploadedFile::fake()->image('test.jpg'),
            'area' => 'Test Area',
            'age' => 25,
        ];

        // 投稿リクエストを送信
        $response = $this->actingAs($user)->post(route('posts.store'), $data);

        // 正しいリダイレクトとセッションメッセージの確認
        $response->assertRedirect(route('posts.index'));
        $response->assertSessionHas('success', '投稿が作成されました');

        // データベースに投稿が存在することを確認
        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'title' => 'Test Post',
            'content' => 'This is a test post.',
            'area' => 'Test Area',
            'age' => 25,
        ]);

        // 保存された投稿の画像が設定されていることを確認
        $post = Post::where('title', 'Test Post')->first();
        $this->assertNotNull($post->image);

        // 実際の画像ファイルがストレージに存在するか確認
        $savedPath = "public/images/{$post->image}";
        $this->assertTrue(Storage::exists($savedPath));
    }

    public function test_update_edits_an_existing_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();

        $data = [
            'title' => 'Updated Title',
            'content' => 'Updated content.',
            'area' => 'Updated Area',
            'age' => 30,
        ];

        // PATCHメソッドに変更
        $response = $this->actingAs($user)->patch(route('posts.update', $post), $data);

        $response->assertRedirect(route('posts.show', $post->id));
        $response->assertSessionHas('success', '編集が完了しました。');
        $this->assertDatabaseHas('posts', ['title' => 'Updated Title']);
    }

    public function test_destroy_deletes_a_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();

        $response = $this->actingAs($user)->delete(route('posts.destroy', $post));

        $response->assertRedirect(route('posts.index'));
        $response->assertSessionHas('success', '投稿が削除されました。');
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}
