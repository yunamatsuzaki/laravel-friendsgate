<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the profile show page is accessible.
     *
     * @return void
     */
    public function test_show_displays_user_profile()
    {
        // ユーザーを作成してログインさせる
        $user = User::factory()->create();
        Auth::login($user);

        // プロフィールページへのアクセスをテスト
        $response = $this->get(route('profile.show'));

        $response->assertStatus(200);
        $response->assertViewHas('post');
        $response->assertViewHas('conversations');
    }

    /**
     * Test that the profile edit page is accessible.
     *
     * @return void
     */
    public function test_edit_displays_edit_form()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $response = $this->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertViewHas('user', $user);
    }

    /**
     * Test that the profile update process works.
     *
     * @return void
     */
    public function test_update_user_profile()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $updatedData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ];

        $response = $this->patch(route('profile.update'), $updatedData);

        $response->assertRedirect(route('profile.edit'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    /**
     * Test that the profile update handles email change correctly.
     *
     * @return void
     */
    public function test_update_user_profile_email_change()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $updatedData = [
            'name' => 'Updated Name',
            'email' => 'newemail@example.com',
        ];

        $response = $this->patch(route('profile.update'), $updatedData);

        $response->assertRedirect(route('profile.edit'));
        $this->assertNull($user->fresh()->email_verified_at);
    }

    /**
     * Test that the user can delete their account.
     *
     * @return void
     */
    public function test_destroy_user_account()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $response = $this->delete(route('profile.destroy'), ['password' => 'password']); // パスワードは適宜変更する必要があります。

        $response->assertRedirect('/');
        $this->assertNull(User::find($user->id));
        $this->assertGuest();
    }
}
