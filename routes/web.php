<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MessageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// 投稿一覧
Route::get('/', [PostController::class, 'index'])->middleware(['auth', 'verified'])->name('posts.index');

// ダッシュボード
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// プロフィールページを表示するルート
Route::get('/profile', [ProfileController::class, 'show'])->middleware('auth')->name('profile.show');

// プロフィール編集用のルート
Route::middleware('auth')->group(function () {
    // プロフィール編集フォームを表示するルート
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    // プロフィール更新のためのルート
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // プロフィール削除のためのルート
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 投稿関連のルート
Route::get('/posts/create', [PostController::class, 'create'])->middleware(['auth', 'verified'])->name('posts.create');
Route::post('/posts', [PostController::class, 'store'])->middleware(['auth', 'verified'])->name('posts.store');
Route::get('/posts/{post}', [PostController::class, 'show'])->middleware(['auth', 'verified'])->name('posts.show');
Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->middleware(['auth', 'verified'])->name('posts.edit');
Route::patch('/posts/{post}', [PostController::class, 'update'])->middleware(['auth', 'verified'])->name('posts.update');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->middleware(['auth', 'verified'])->name('posts.destroy');

// メッセージ関連のルート
Route::post('/messages', [MessageController::class, 'store'])->middleware(['auth', 'verified'])->name('messages.store');
Route::get('/messages/inbox', [MessageController::class, 'inbox'])->middleware(['auth', 'verified'])->name('messages.inbox');
Route::get('/messages/sent', [MessageController::class, 'sent'])->middleware(['auth', 'verified'])->name('messages.sent');

Route::get('/chat/{user}', [MessageController::class, 'chat'])->name('messages.chat');

require __DIR__ . '/auth.php';

// 投稿関連のルート
Route::get('/posts', [PostController::class, 'index'])->middleware(['auth', 'verified'])->name('posts.index');
Route::get('/posts/create', [PostController::class, 'create'])->middleware(['auth', 'verified'])->name('posts.create');
Route::post('/posts', [PostController::class, 'store'])->middleware(['auth', 'verified'])->name('posts.store');
Route::get('/posts/{post}', [PostController::class, 'show'])->middleware(['auth', 'verified'])->name('posts.show');
Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->middleware(['auth', 'verified'])->name('posts.edit');
Route::patch('/posts/{post}', [PostController::class, 'update'])->middleware(['auth', 'verified'])->name('posts.update');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->middleware(['auth', 'verified'])->name('posts.destroy');

// メッセージ関連のルート
Route::post('/messages', [MessageController::class, 'store'])->middleware(['auth', 'verified'])->name('messages.store');
Route::get('/messages/inbox', [MessageController::class, 'inbox'])->middleware(['auth', 'verified'])->name('messages.inbox');
Route::get('/messages/sent', [MessageController::class, 'sent'])->middleware(['auth', 'verified'])->name('messages.sent');

Route::get('/chat/{user}', [MessageController::class, 'chat'])->name('messages.chat');
