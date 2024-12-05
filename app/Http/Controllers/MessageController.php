<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class MessageController extends Controller
{
    // メッセージ送信処理
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90', // 緯度バリデーション
            'longitude' => 'nullable|numeric|between:-180,180', // 経度バリデーション
            'meeting_time' => 'nullable|date',
        ]);

        $message = new Message();
        $message->sender_id = Auth::id();
        $message->receiver_id = $request->receiver_id;
        $message->location = $request->location;
        $message->latitude = $request->latitude; // 緯度を保存
        $message->longitude = $request->longitude; // 経度を保存
        $message->meeting_time = $request->meeting_time;
        $message->content = $request->content;
        $message->save();

        // フラッシュメッセージの設定
        return redirect()->back()->with('success', 'メッセージが送信されました。');
    }

    // 受信メッセージ一覧表示
    public function inbox()
    {
        $userId = Auth::id();

        // 受信したメッセージと送信したメッセージをまとめて取得し、ページネーションを適用
        $messages = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->paginate(8);  // 1ページあたり15件のメッセージ

        // ユーザーごとにメッセージをグループ化
        $conversations = $messages->groupBy(function ($message) use ($userId) {
            return $message->sender_id == $userId ? $message->receiver_id : $message->sender_id;
        })->map(function ($messages) {
            return [
                'user' => $messages->first()->sender_id != auth()->id() ? $messages->first()->sender : $messages->first()->receiver,
                'last_message' => $messages->sortByDesc('created_at')->first(),
            ];
        });

        return view('messages.inbox', compact('conversations', 'messages'));
    }

    // チャット画面（選択したユーザーとのメッセージ）
    public function chat(User $user)
    {
        $currentUserId = Auth::id();

        // 自分と選択した相手のメッセージを取得
        $messages = Message::where(function ($query) use ($currentUserId, $user) {
            $query->where('sender_id', $currentUserId)
                ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($currentUserId, $user) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', $currentUserId);
        })->orderBy('created_at')->get();

        return view('messages.chat', [
            'chatPartner' => $user,
            'messages' => $messages
        ]);
    }
}
