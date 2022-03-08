<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('unauthorized', [AuthController::class, 'unauthorized'])->name('unauthorized');

Route::post('/auth/login', [AuthController::class, 'login'])->name('login');
Route::post('/auth/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/auth/refresh', [AuthController::class, 'refresh'])->name('refresh');
Route::post('/user', [AuthController::class, 'create'])->name('create');
Route::put('/user', [UserController::class, 'update'])->name('updatePut');
Route::post('/user/avatar', [UserController::class, 'updateAvatar'])->name('updateAvatar');
Route::post('/user/cover', [UserController::class, 'updateCover'])->name('updateCover');

/*Route::get('/feed', [FeedController::class, 'read'])->name('feedRead');
Route::get('/user/feed', [FeedController::class, 'userFeed'])->name('userFeed');
Route::get('/user/{id}/feed', [FeedController::class, 'userFeed'])->name('userFeed');

Route::get('/user', [UserController::class, 'read'])->name('userInformation');
Route::get('/user/{id}', [UserController::class, 'read'])->name('userInformation');

Route::post('/feed', [FeedController::class, 'create'])->name('createFeed');

Route::post('/post/{id}/like', [PostController::class, 'like'])->name('like');
Route::post('/post/{id}/comment', [PostController::class, 'comment'])->name('comment');

Route::get('/search', [SearchController::class, 'search'])->name('search');

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/
