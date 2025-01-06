@extends('layouts.app') <!-- レイアウトを適用 -->

@section('content') <!-- メインコンテンツを定義 -->
<main class="container mt-5">
    <!-- フラッシュメッセージの表示 -->
    <!-- @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif -->

    <h1 class="mb-4">チャット</h1>
    <a href="{{ route('messages.inbox') }}" class="btn btn-secondary mb-4">&lt; 戻る</a>

    <!-- メッセージ履歴表示 -->
    <div class="mb-5">
        <h3>
            チャット相手: {{ $chatPartner->name }}
        </h3>
        <div class="list-group">
            @foreach($messages as $message)
            <div class="list-group-item {{ $message->sender_id === Auth::id() ? 'text-end' : '' }}">
                <strong>{{ $message->sender_id === Auth::id() ? 'あなた' : $chatPartner->name }}</strong>
                <p class="mb-1">{{ $message->content }}</p>&nbsp; &nbsp; &nbsp; 

                <!-- 会う場所の表示 -->
                @if($message->location)
                <p class="text-muted mb-1"> 
                    <strong>会う場所:</strong> {{ $message->location }}
                </p>
                @endif

                <!-- 会う日時の表示 -->
                @if($message->meeting_time)
                <p class="text-muted">
                    <strong>会う日時:</strong> {{ \Carbon\Carbon::parse($message->meeting_time)->format('Y年m月d日 H:i') }}
                </p>
                @endif

                <!-- Googleマップ表示 -->
                @if($message->latitude && $message->longitude)
                <div class="mt-3" id="map-{{ $message->id }}" style="height: 300px;"></div>
                <script>
                    function initMap() {
                        const mapElement = document.getElementById('map-{{ $message->id }}');
                        const coordinates = { lat: {{ $message->latitude }}, lng: {{ $message->longitude }} };

                        const map = new google.maps.Map(mapElement, {
                            center: coordinates,
                            zoom: 15
                        });

                        new google.maps.Marker({
                            position: coordinates,
                            map: map,
                            title: '{{ $message->location }}'
                        });
                    }

                    window.onload = initMap;
                </script>
                @endif

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

        <button type="submit" class="btn btn-regular">送信</button>
    </form>
</main>

{{-- Google Maps APIのスクリプト --}}
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDf4m6hFXLLLbQ0786HJ4mZ8-zVvxbwyQs&callback=initMap&libraries=places" async defer></script>

@endsection
