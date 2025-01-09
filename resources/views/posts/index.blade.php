@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">投稿一覧</h1>

    <!-- 年齢・エリア・タイトルの絞り込みフォーム -->
    <form action="{{ route('posts.index') }}" method="GET" class="mb-4">
        <div class="row g-2 align-items-end">
            <!-- 年齢 -->
            <div class="col-md-4">
                <label for="age" class="form-label">年齢</label>
                <input type="number" name="age" id="age" class="form-control" placeholder="年齢で絞り込む" value="{{ request('age') }}">
            </div>

            <!-- エリア -->
            <div class="col-md-4">
                <label for="area" class="form-label">エリア</label>
                <input type="text" name="area" id="area" class="form-control" placeholder="エリアで絞り込む" value="{{ request('area') }}">
            </div>

            <!-- タイトル -->
            <div class="col-md-4">
                <label for="title" class="form-label">タイトル</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="タイトルで絞り込む" value="{{ request('title') }}">
            </div>
        </div>

        <!-- 絞り込みボタンとリセットボタンを下に配置 -->
        <div class="mt-3 text-center">
            <button type="submit" class="btn btn-regular me-2">絞り込む</button>
            <a href="{{ route('posts.index') }}" class="btn btn-secondary">リセット</a>
        </div>
    </form>

    <div class="row">
        @foreach($posts as $post)
        <div class="col-md-4 mb-4">
            <div class="card">
                <!-- 画像の表示 -->
                <div class="image-wrapper">
                    @if($post->image)
                    <img src="{{ asset('storage/images/' . $post->image) }}" class="detail-image" alt="投稿画像">
                    @else
                    <img src="{{ asset('img/' . basename($randomImages[array_rand($randomImages)]->getFilename())) }}" class="detail-image" alt="ランダム画像">
                    @endif
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $post->user->name }}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">{{ $post->title }}</h6>
                    <!-- 年齢とエリアの表示 -->
                    <p class="card-text"><strong>年齢:</strong> {{ $post->age }}歳</p>
                    <p class="card-text"><strong>エリア:</strong> {{ $post->area }}</p>
                    <a href="{{ route('posts.show', $post) }}" class="btn btn-regular">詳細</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- ページネーションリンクの追加 -->
    <div class="d-flex justify-content-center mt-4 pagination">
        {{ $posts->links() }}
    </div>
</div>
@endsection