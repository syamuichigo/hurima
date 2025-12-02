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
Route::get('/', [ContentController::class, 'index']);
route::get('/mypage', [ContentController::class, 'mypage']);
route::get('/sell',[ContentController::class, 'sell']);
route::get('/profile',[ContentController::class, 'profile']);
route::get('purchase',[ContentController::class, 'purchase']);
route::get('/item',[ContentController::class, 'item']);
route::get('/address',[ContentController::class, 'address']);