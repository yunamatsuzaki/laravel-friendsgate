@extends('layouts.app') <!-- レイアウトを適用 -->

@section('content') <!-- メインコンテンツを定義 -->
<main class="container mt-5">
    <h1 class="mb-4">投稿詳細</h1>
    <a href="{{ route('posts.index') }}" class="btn btn-secondary mb-4">&lt; 戻る</a>

    <div class="post-detail d-flex flex-wrap">
        <!-- 画像部分 -->
        <div class="image-wrapper">
            @if($post->image)
            <img src="{{ asset('storage/images/' . $post->image) }}" alt="投稿画像" class="detail-image">
            @else
            <img src="{{ asset('img/' . basename($randomImages[array_rand($randomImages)]->getFilename())) }}" alt="ランダム画像" class="detail-image">
            @endif
        </div>

        <!-- テキスト部分 -->
        <div class="content-wrapper">
            <h2>{{ $post->user->name }}の投稿</h2>
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
            @if($post->user_id !== Auth::id()) <!-- 自分以外の投稿の場合 -->
            <button id="request-btn" class="btn btn-regular mt-3">リクエストを送る</button>
            @endif

            <!-- メッセージ入力フォーム -->
            <div id="message-form" style="display: none; margin-top: 20px;">
                <form action="{{ route('messages.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $post->user_id }}">
                    <input type="hidden" id="latitude" name="latitude">
                    <input type="hidden" id="longitude" name="longitude">

                    <div class="mb-3">
                        <label for="message-content" class="form-label">メッセージを送る</label>
                        <textarea id="message-content" name="content" class="form-control" rows="3" required></textarea>
                    </div>

                    <!-- Google Mapsと場所選択 -->
                    <div class="mb-3">

                        <label for="location" class="form-label">会う場所</label>
                        <input type="text" id="location" name="location" class="form-control" placeholder="会う場所を入力" required>
                        <div id="map" style="height: 300px; margin-top: 10px;"></div>
                    </div>

                    <div class="mb-3">
                        <label for="meeting_time" class="form-label">会う日時</label>
                        <input type="datetime-local" id="meeting_time" name="meeting_time" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-regular">送信</button>
                </form>
            </div>
        </div>
    </div>
</main>

{{-- Google Maps APIのスクリプト --}}
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDf4m6hFXLLLbQ0786HJ4mZ8-zVvxbwyQs&libraries=places&&language=ja&callback=initMap" async defer></script>
<script>
 let map, marker, autocomplete, geocoder;

function initMap() {
    const defaultLocation = { lat: 35.681236, lng: 139.767125 }; // 初期位置: 東京駅

    // ジオコーダーの初期化（日本語対応）
    geocoder = new google.maps.Geocoder();

    // 地図を初期化
    map = new google.maps.Map(document.getElementById('map'), {
        center: defaultLocation,
        zoom: 15,
    });

    // マーカーを初期化
    marker = new google.maps.Marker({
        position: defaultLocation,
        map: map,
        draggable: true,
    });

        // オートコンプリートの初期化 (日本語対応＋日本国内限定)
        const input = document.getElementById('location');
        autocomplete = new google.maps.places.Autocomplete(input, {
            language: 'ja',  // 日本語で表示
            componentRestrictions: { country: 'JP' } // 日本国内に制限
        });
        autocomplete.bindTo('bounds', map);

    // オートコンプリートで場所選択時の処理
    autocomplete.addListener('place_changed', () => {
        const place = autocomplete.getPlace();
        if (place.geometry) {
            const location = place.geometry.location;
            map.setCenter(location);
            marker.setPosition(location);

            document.getElementById('latitude').value = location.lat();
            document.getElementById('longitude').value = location.lng();
        } else {
            alert("有効な場所を選択してください");
        }
    });

    // マーカーの位置変更時に緯度経度と日本語住所を更新
    google.maps.event.addListener(marker, 'dragend', function () {
        const position = marker.getPosition();
        document.getElementById('latitude').value = position.lat();
        document.getElementById('longitude').value = position.lng();

        // ジオコーダーで日本語の住所を取得して表示
        geocoder.geocode({ location: position }, (results, status) => {
                if (status === "OK" && results[0]) {
                    document.getElementById('location').value = results[0].formatted_address;
                } else {
                    alert("住所を取得できませんでした");
                }
            }
        );
    });
}

// メッセージフォームの表示切替
document.getElementById('request-btn').addEventListener('click', function () {
    document.getElementById('message-form').style.display = 'block';
    this.style.display = 'none';
});

// ページロード時に地図を初期化
window.initMap = initMap;
</script>
@endsection
