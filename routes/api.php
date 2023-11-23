<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\PostController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::get('posts/{offset}/{id?}',[PostController::class,'index']);
Route::get('view-post/{key}/{showAuthorPosts}/{userId?}',[PostController::class,'show']);


Route::middleware('auth:sanctum')->group( function () {
    Route::put('update-post/{id}',[PostController::class,'update']);
    Route::post('save-post',[PostController::class,'store']);
    Route::delete('delete-post/{id}',[PostController::class,'destroy']);
    Route::post('post-like-status',[PostController::class,'likeStatus']);
});

