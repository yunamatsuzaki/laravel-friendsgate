<?php

namespace Database\Factories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition()
    {
        return [
            'sender_id' => \App\Models\User::factory(), // または既存のユーザーIDを指定
            'receiver_id' => \App\Models\User::factory(), // 同上
            'content' => $this->faker->sentence,
            'location' => $this->faker->city,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'meeting_time' => $this->faker->dateTime,
        ];
    }
}