<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;
use App\Models\User;
use App\Models\Message;

class ProfileController extends Controller
{
    // Myページ
    public function show()
    {
        // ログインユーザーの投稿を取得（ユーザーが投稿していない場合はnull）
        $post = auth()->user()->post; // User モデルに定義したリレーションを使う

        // ユーザーとの会話を取得
        $conversations = Message::where('receiver_id', Auth::id())
            ->orWhere('sender_id', Auth::id())
            ->with(['sender', 'receiver'])  // 送信者と受信者情報を一緒に取得
            ->latest() // 新しい順に並べる
            ->get()
            ->groupBy(function ($message) {
                return $message->sender_id == Auth::id() ? $message->receiver_id : $message->sender_id;
            });

        // ランダム画像を取得
        $randomImages = $this->getRandomImages();

        // ビューに投稿、会話、ランダム画像を渡す
        return view('profile.show', compact('post', 'conversations', 'randomImages'));
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Get a list of random images from the 'img' directory.
     */
    private function getRandomImages()
    {
        $imagesPath = public_path('img');
        $allImages = File::files($imagesPath);

        $validImages = array_filter($allImages, function ($file) {
            return in_array($file->getExtension(), ['jpg', 'jpeg', 'png', 'gif']);
        });

        return $validImages;
    }
}