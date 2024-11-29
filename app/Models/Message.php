<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id', 'receiver_id', 'content', 'location', 'meeting_time'
    ];

    // 送信者とのリレーション
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // 受信者とのリレーション
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // 会う日時のキャスト
    protected $casts = [
        'meeting_time' => 'datetime',
        'created_at' => 'datetime',
    ];
}