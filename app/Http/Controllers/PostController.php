<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Post;

class PostController extends Controller
{
    public function index(Request $request)
    {
        // 絞り込みのためのクエリビルダーを作成
        $query = Post::with('user');

        // 年齢の絞り込み
        if ($request->filled('age')) {
            $query->where('age', $request->age);
        }

        // エリアの絞り込み（部分一致）
        if ($request->filled('area')) {
            $query->where('area', 'LIKE', '%' . $request->area . '%');
        }

        // タイトルの絞り込み（部分一致）
        if ($request->filled('title')) {
            $query->where('title', 'LIKE', '%' . $request->title . '%');
        }

        // 結果の取得とページネーション
        $posts = $query->orderBy('created_at', 'desc')->paginate(9);

        // ランダム画像の取得
        $randomImages = $this->getRandomImages();

        return view('posts.index', compact('posts', 'randomImages'));
    }

    public function show(Post $post)
    {
        $randomImages = $this->getRandomImages(); // これを追加してランダム画像を取得

        return view('posts.show', compact('post', 'randomImages'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->post) {
            return redirect()->route('posts.edit', $user->post->id)
                ->with('info', 'すでに投稿があります。編集ページに移動しました。');
        }

        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'area' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:0',
        ]);

        $post = new Post();
        $post->user_id = Auth::id();
        $post->title = $request->title;
        $post->content = $request->content;
        $post->area = $request->area;
        $post->age = $request->age;


        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/images');
            $post->image = basename($path);
        }

        $post->save();

        return redirect()->route('posts.index')->with('success', '投稿が作成されました');
    }

    public function edit(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('posts.index')->with('error', '他のユーザーの投稿は編集できません');
        }

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('posts.index')->with('error', '他のユーザーの投稿は編集できません');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'area' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:0',

        ]);

        $post->title = $request->title;
        $post->content = $request->content;
        $post->area = $request->area;
        $post->age = $request->age;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/images');
            $post->image = basename($path);
        }

        $post->save();

        session()->flash('success', '編集が完了しました。');

        return redirect()->route('posts.show', $post->id)->with('success', '編集が完了しました。');
    }

    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('posts.index')->with('error_message', '不正なアクセスです。');
        }

        $post->delete();

        session()->flash('success', '投稿が削除されました。');

        return redirect()->route('posts.index')->with('success', '投稿が削除されました。');
    }

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
