@extends('layouts.app') <!-- レイアウトを適用 -->

@section('content') <!-- メインコンテンツを定義 -->
    <main class="container mt-5">
        <!-- フラッシュメッセージの表示 -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <h1 class="mb-4">チャット</h1>
        <a href="{{ route('messages.inbox') }}" class="btn btn-secondary mb-4">&lt; 戻る</a>

        <!-- メッセージ履歴表示 -->
        <div class="mb-5">
            <h3>チャット相手: {{ $chatPartner->name }}</h3>
            <div class="list-group">
                @foreach($messages as $message)
                    <div class="list-group-item {{ $message->sender_id === Auth::id() ? 'text-end' : '' }}">
                        <strong>{{ $message->sender_id === Auth::id() ? 'あなた' : $chatPartner->name }}</strong>
                        <p class="mb-1">{{ $message->content }}</p>
                        <small>{{ $message->created_at->format('Y-m-d H:i') }}</small>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- メッセージ送信フォーム -->
        <form action="{{ route('messages.store') }}" method="POST">
            @csrf
            <input type="hidden" name="receiver_id" value="{{ $chatPartner->id }}">

            <div class="mb-3">
                <textarea name="content" class="form-control" rows="3" placeholder="メッセージを入力" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">送信</button>
        </form>
    </main>
@endsection