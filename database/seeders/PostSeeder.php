<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // 追加で20人のダミーユーザーを作成
        User::factory(20)->create()->each(function ($user) {
            // ユーザーごとに1つの投稿を作成
            Post::factory()->create([
                'user_id' => $user->id, // 作成したユーザーIDを関連付け
            ]);
        });
    }
}