<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_creates_a_new_message()
    {
        $user = User::factory()->create();
        $receiver = User::factory()->create();
        Auth::login($user);

        $data = [
            'content' => 'Hello, this is a test message.',
            'receiver_id' => $receiver->id,
            'location' => 'Test Location',
            'latitude' => 35.6895,
            'longitude' => 139.6917,
            'meeting_time' => now()->addDay(),
        ];

        $response = $this->post(route('messages.store'), $data);

        $response->assertRedirect()->with('success', 'メッセージが送信されました。');
        $this->assertDatabaseHas('messages', [
            'sender_id' => $user->id,
            'receiver_id' => $receiver->id,
            'content' => 'Hello, this is a test message.',
        ]);
    }

    public function test_inbox_displays_messages()
    {
        $user = User::factory()->create();
        $receiver = User::factory()->create();
        Auth::login($user);

        // メッセージを作成
        $message = Message::factory()->create([
            'sender_id' => $user->id,
            'receiver_id' => $receiver->id,
            'content' => 'This is a test message.',
        ]);

        $response = $this->get(route('messages.inbox'));

        $response->assertStatus(200);
        $response->assertViewHas('conversations');
        $response->assertViewHas('messages');
    }

    public function test_chat_displays_messages_between_users()
    {
        $user = User::factory()->create();
        $chatPartner = User::factory()->create();
        Auth::login($user);

        // 2つのユーザー間でメッセージを作成
        $message1 = Message::factory()->create([
            'sender_id' => $user->id,
            'receiver_id' => $chatPartner->id,
            'content' => 'Hello, how are you?',
        ]);
        $message2 = Message::factory()->create([
            'sender_id' => $chatPartner->id,
            'receiver_id' => $user->id,
            'content' => 'I am fine, thank you!',
        ]);

        $response = $this->get(route('messages.chat', $chatPartner));

        $response->assertStatus(200);
        $response->assertViewHas('chatPartner', $chatPartner);
        $response->assertViewHas('messages');
    }
}
