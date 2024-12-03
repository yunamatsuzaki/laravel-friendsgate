@extends('layouts.app')

@section('content')
<main class="container mt-5">
    <h1 class="mb-4">My Page</h1>

    <div class="row">
        <!-- 左側: ユーザー情報と投稿欄 -->
        <div class="col-md-6">
            <!-- ユーザー情報 -->
            <div class="card mb-3">
                <div class="card-body">
                    <h3 class="card-title">ユーザー情報</h3>
                    <p><strong>名前:</strong> {{ Auth::user()->name }}</p>
                    <p><strong>メールアドレス:</strong> {{ Auth::user()->email }}</p>
                    <p class="mt-4">
                        <a href="{{ route('profile.edit') }}" class="btn btn-regular">プロフィールを編集</a>
                    </p>
                </div>
            </div>

            <!-- 投稿欄 -->
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">投稿</h3>
                    <div class="row justify-content-center">
                        @if($post)
                            <div class="col-md-10">
                                <div class="card">
                                    <div class="image-wrapper" style="text-align: center;">
                                        @if($post->image)
                                            <img src="{{ asset('storage/images/' . $post->image) }}" alt="投稿画像" class="img-fluid" />
                                        @else
                                            <img src="{{ asset('img/no_image.png') }}" alt="デフォルト画像" class="img-fluid" />
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $post->title }}</h5>
                                        <p class="card-text"><strong>年齢:</strong> {{ $post->age }}歳</p>
                                        <p class="card-text"><strong>エリア:</strong> {{ $post->area }}</p>
                                        <a href="{{ route('posts.show', $post) }}" class="btn btn-regular">詳細</a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('posts.create') }}" class="btn btn-regular">投稿作成する</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- 右側: メッセージ欄 -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="card-title">メッセージ</h3>

                    @if($conversations->count() > 0)
                        <div class="overflow-auto" style="max-height: 600px;">
                            @foreach($conversations as $conversation)
                                @php
                                    $lastMessage = $conversation->last(); 
                                    $partner = $lastMessage->sender_id == auth()->id() ? $lastMessage->receiver : $lastMessage->sender;
                                @endphp
                                <a href="{{ route('messages.chat', $partner->id) }}" class="list-group-item list-group-item-action mb-2">
                                    <h5 class="mb-1">{{ $partner->name }} とのメッセージ</h5>
                                    <p class="mb-1">
                                        @if($lastMessage->sender_id == auth()->id())
                                            あなた: {{ $lastMessage->content }}
                                        @else
                                            {{ $partner->name }}: {{ $lastMessage->content }}
                                        @endif
                                    </p>
                                    <small>{{ $lastMessage->created_at->format('Y-m-d H:i') }}</small>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p>メッセージはありません。</p>
                    @endif

                    <a href="{{ route('messages.inbox') }}" class="btn btn-regular mt-3">メッセージ一覧を見る</a>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
