<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\LikeDislikeController;
use App\Http\Controllers\TagsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    //Tags Endpoint
    Route::get('/tags', [TagsController::class, 'index']);
    Route::post('/tags', [TagsController::class, 'store']);

    //Articles Endpoints
    Route::get('/articles/search', [ArticleController::class, 'search']);

    Route::resource('articles', ArticleController::class);

    Route::post('/articles/{article}/upvote', [LikeDislikeController::class, 'upvote']);
    Route::post('/articles/{article}/downvote', [LikeDislikeController::class, 'downvote']);

    //comments Endpoint
    Route::post('/articles/{article}/comments', [CommentsController::class, 'store']);
    Route::put('/comments/{comment}', [CommentsController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentsController::class, 'destroy']);
});
