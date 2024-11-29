@extends('layouts.app') <!-- レイアウトを指定 -->

@section('content') <!-- コンテンツを指定 -->
    <div class="container mt-5">
        <h1 class="mb-4">投稿編集</h1>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary mb-4">&lt; 戻る</a>

        <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="mb-3">
                <label for="title" class="form-label">タイトル</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $post->title) }}">
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">内容</label>
                <textarea name="content" id="content" class="form-control" rows="5">{{ old('content', $post->content) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">画像</label>
                <input type="file" name="image" id="image" class="form-control">
                @if($post->image)
                    <p>現在の画像:</p>
                    <img src="{{ asset('storage/images/' . $post->image) }}" alt="投稿画像" style="width: 100px;">
                @endif
            </div>

            <div class="mb-3">
                <label for="area" class="form-label">地域</label>
                <input type="text" name="area" id="area" class="form-control" value="{{ old('area', $post->area) }}">
            </div>

            <div class="mb-3">
                <label for="age" class="form-label">年齢</label>
                <input type="number" name="age" id="age" class="form-control" value="{{ old('age', $post->age) }}">
            </div>

            <button type="submit" class="btn btn-regular">投稿を更新</button>
        </form>
    </div>
@endsection