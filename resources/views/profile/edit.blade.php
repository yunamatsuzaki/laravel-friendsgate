@extends('layouts.app') <!-- レイアウトを適用 -->

@section('content') <!-- メインコンテンツを定義 -->
    <main class="container mt-5">
        <h1 class="mb-4 text-center">プロフィール編集</h1>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- プロフィール情報更新フォーム -->
                <div class="mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <!-- プロフィール情報更新の部分 -->
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>

                <!-- パスワード更新フォーム -->
                <div class="mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <!-- パスワード更新の部分 -->
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

                <!-- アカウント削除フォーム -->
                <div class="mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <!-- アカウント削除の部分 -->
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection