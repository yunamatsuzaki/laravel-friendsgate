@extends('layouts.app') <!-- レイアウトを適用 -->

@section('content') <!-- メインコンテンツを定義 -->
    <main class="container mt-5">
        <h1>新しい投稿を作成</h1>
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">タイトル</label>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">内容</label>
                <textarea id="content" name="content" class="form-control" required>{{ old('content') }}</textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">画像</label>
                <input type="file" id="image" name="image" class="form-control" accept="image/*">
            </div>
            <div class="mb-3">
                <label for="area" class="form-label">地域</label>
                <input type="text" id="area" name="area" class="form-control" value="{{ old('area') }}">
            </div>
            <div class="mb-3">
                <label for="age" class="form-label">年齢</label>
                <input type="number" id="age" name="age" class="form-control" value="{{ old('age') }}">
            </div>
            <button type="submit" class="btn btn-primary">投稿する</button>
        </form>
    </main>
@endsection