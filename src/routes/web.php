<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::middleware('auth')->group(function () {
//     Route::get('/', [ContentController::class, 'index']);
// });

// 公開ページ（認証不要）
Route::get('/', [ContentController::class, 'index']);
Route::get('/search', [ContentController::class, 'search']);
Route::get('/item/{id}', [ContentController::class, 'item']);

// メール認証関連（認証済みだがメール認証未完了のユーザーもアクセス可能）
Route::middleware('auth')->group(function () {
    Route::post('/email/verify-complete', [ContentController::class, 'verifyEmail'])->name('email.verify.complete');
});

// 認証が必要なページ（ログイン + メール認証必須）
Route::middleware(['auth', 'verified'])->group(function () {
    // マイページ関連
    Route::get('/mypage', [ContentController::class, 'mypage']);
    Route::get('/profile', [ContentController::class, 'profile']);
    Route::post('/profile', [ContentController::class, 'profile']);
    Route::post('/profile-update', [ContentController::class, 'profileUpdate']);
    Route::get('/address', [ContentController::class, 'address']);
    Route::post('/address/update', [ContentController::class, 'addressUpdate']);
    
    // 出品関連
    Route::get('/sell', [ContentController::class, 'sell']);
    Route::post('/sell', [ContentController::class, 'sellStore']);
    
    // 購入関連（表示: GET /purchase, 処理: POST /purchase）
    Route::get('/purchase', [ContentController::class, 'purchase_buy'])->name('purchase.show');
    Route::post('/purchase', [ContentController::class, 'purchase_store'])->name('purchase.store');
    Route::get('/purchase/address/{id}', [ContentController::class, 'purchaseFromAddress'])->name('purchase.from.address');
    Route::get('/thanks', [ContentController::class, 'thanks']);
    
    // 初回プロフィール登録ページ
    Route::get('/setup', [ContentController::class, 'setup']);
    Route::post('/setup_create', [ContentController::class, 'setup_create']);
    
    // お気に入り・コメント機能
    Route::post('/favorite/toggle', [ContentController::class, 'toggleFavorite']);
    Route::post('/comment/store', [ContentController::class, 'commentStore']);
});