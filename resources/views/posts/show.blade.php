@extends('layouts.app') <!-- レイアウトを適用 -->

@section('content') <!-- メインコンテンツを定義 -->
    <main class="container mt-5">
        <h1 class="mb-4">投稿詳細</h1>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary mb-4">&lt; 戻る</a>

        <div class="d-flex">
            <!-- 画像部分 -->
            <div class="image-wrapper" style="margin-right: 20px; flex-shrink: 0;">
            @if($post->image)
                <img src="{{ asset('storage/images/' . $post->image) }}" alt="投稿画像" class="detail-image" style="max-width: 300px; object-fit: cover; border-radius: 8px;">
            @else
                <img src="{{ asset('img/no_image.png') }}" alt="デフォルト画像" class="detail-image" style="max-width: 300px; object-fit: cover; border-radius: 8px;">
            @endif
        </div>

            <!-- テキスト部分 -->
            <div class="content-wrapper" style="flex-grow: 1;">
            <h2> {{ $post->user->name }}の投稿</h2>
                <h3>{{ $post->title }}</h3>
                <p><strong>地域:</strong> {{ $post->area }}</p>
                <p><strong>年齢:</strong> {{ $post->age }}歳</p>
                <p>{{ $post->content }}</p>

                <!-- 自分の投稿の場合のみ編集と削除ボタンを表示 -->
                @if($post->user_id === Auth::id())
                    <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning">編集</a>

                    <!-- 削除ボタン -->
                    <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('本当に削除してもよろしいですか？');" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">削除</button>
                    </form>
                @endif

                <!-- リクエストを送るボタン -->
                @if($post->user_id !== Auth::id())  <!-- 自分以外の投稿の場合 -->
                    <button id="request-btn" class="btn btn-regular mt-3">リクエストを送る</button>
                @endif

                <!-- メッセージ入力フォーム（非表示） -->
                <div id="message-form" style="display: none; margin-top: 20px;">
                    <form action="{{ route('messages.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $post->user_id }}">

                        <div class="mb-3">
                            <label for="message-content" class="form-label">メッセージを送る</label>
                            <textarea id="message-content" name="content" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">会う場所</label>
                            <input type="text" id="location" name="location" class="form-control" placeholder="会う場所を入力">
                        </div>

                        <div class="mb-3">
                            <label for="meeting_time" class="form-label">会う日時</label>
                            <input type="datetime-local" id="meeting_time" name="meeting_time" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-regular">送信</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    {{-- メッセージフォームの表示/非表示 --}}
    <script>
        document.getElementById('request-btn').addEventListener('click', function() {
            // メッセージフォームを表示
            document.getElementById('message-form').style.display = 'block';
            // 「リクエストを送る」ボタンを非表示に
            document.getElementById('request-btn').style.display = 'none';
        });
    </script>
@endsection