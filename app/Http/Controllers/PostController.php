<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\User;
use App\Http\Requests\PostRequest;

class PostController extends Controller
{
    public function index()
{
    // 投稿を新しい順に並べて取得
    $posts = Post::with('user') // 投稿に紐づくユーザーも一緒にロード
        ->orderBy('created_at', 'desc') // 新しい順
        ->paginate(9); // ページネーション

    return view('posts.index', compact('posts'));
}

    // 詳細ページ
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    // 新しい投稿の作成フォームを表示
    public function create()
    {
        $user = Auth::user();

        // 既に投稿がある場合は編集ページにリダイレクト
        if ($user->post) {
            return redirect()->route('posts.edit', $user->post->id)
                ->with('info', 'すでに投稿があります。編集ページに移動しました。');
        }

        return view('posts.create');
    }

    // 投稿を保存
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 画像のバリデーション
            'area' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:0',
        ]);

        $post = new Post();
        $post->user_id = Auth::id();
        $post->title = $request->title;
        $post->content = $request->content;
        $post->area = $request->area;
        $post->age = $request->age;

        // 画像を保存
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/images'); // 画像を保存してパスを取得
            $post->image = basename($path); // 画像のファイル名を保存
        }

        $post->save();

        return redirect()->route('posts.index')->with('success', '投稿が作成されました');
    }

    // 投稿の編集フォーム
    public function edit(Post $post)
    {
        // 自分の投稿でない場合は、編集できないようにリダイレクト
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('posts.index')->with('error', '他のユーザーの投稿は編集できません');
        }

        return view('posts.edit', compact('post'));
    }

    // 投稿の更新
    public function update(Request $request, Post $post)
    {
        // 自分の投稿でない場合は、編集できないようにリダイレクト
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('posts.index')->with('error', '他のユーザーの投稿は編集できません');
        }

        // バリデーション
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 画像のバリデーション
            'area' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:0',
        ]);

        // 投稿の更新
        $post->title = $request->title;
        $post->content = $request->content;
        $post->area = $request->area;
        $post->age = $request->age;

        // 画像の更新
        if ($request->hasFile('image')) {
            // 新しい画像を保存
            $path = $request->file('image')->store('public/images');
            $post->image = basename($path); // 新しい画像ファイル名を保存
        }

        $post->save(); // 更新を保存

        // フラッシュメッセージを設定
        session()->flash('success', '編集が完了しました。'); // こちらでも設定して確認可能

        return redirect()->route('posts.show', $post->id)->with('success', '編集が完了しました。');
    }

    // 削除機能
    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('posts.index')->with('error_message', '不正なアクセスです。');
        }

        // 投稿を削除
        $post->delete();

        // フラッシュメッセージを設定
        session()->flash('success', '投稿が削除されました。'); // こちらでも設定して確認可能

        return redirect()->route('posts.index')->with('success', '投稿が削除されました。');
    }
}