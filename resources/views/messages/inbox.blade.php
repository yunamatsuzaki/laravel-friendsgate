@extends('layouts.app') <!-- レイアウトを適用 -->

@section('content') <!-- メインコンテンツを定義 -->
    <main class="container mt-5">
        <h1 class="mb-4">メッセージ一覧</h1>

        <a href="{{ route('posts.index') }}" class="btn btn-secondary mb-4">&lt; 戻る</a>

        <!-- ユーザーとのメッセージのやり取りを表示 -->
        <section>
            @if($conversations->count() > 0)
                @foreach($conversations as $conversation)
                    <div class="list-group mb-3">
                        <a href="{{ route('messages.chat', $conversation['user']->id) }}" class="list-group-item list-group-item-action">
                            <h5 class="mb-1">{{ $conversation['user']->name }} とのメッセージ</h5>
                            <p class="mb-1">
                                @if($conversation['last_message']->sender_id == auth()->id())
                                    あなた: {{ $conversation['last_message']->content }}
                                @else
                                    {{ $conversation['user']->name }}: {{ $conversation['last_message']->content }}
                                @endif
                            </p>
                            <small>{{ $conversation['last_message']->created_at->format('Y-m-d H:i') }}</small>
                        </a>
                    </div>
                @endforeach

                <!-- ページネーションのリンク -->
                <div class="d-flex justify-content-center">
                    {{ $messages->links() }}
                </div>

            @else
                <p>メッセージはありません。</p>
            @endif
        </section>
    </main>
@endsection