@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">投稿一覧</h1>

        <div class="row">
            @foreach($posts as $post)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <!-- 画像の表示 -->
                        <div class="image-wrapper">
                            @if($post->image)
                                <img src="{{ asset('storage/images/' . $post->image) }}" class="detail-image" alt="投稿画像">
                            @else
                                <img src="{{ asset('storage/images/no_image.png') }}" class="detail-image" alt="デフォルト画像">
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