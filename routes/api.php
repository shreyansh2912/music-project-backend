<?php

use App\Http\Controllers\MusicListController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register',[UserController::class,'store']);

Route::post('/login',[UserController::class,'login']);

Route::middleware(['session_check'])->group(function () {

    Route::get('/music-list', [MusicListController::class, 'index']);

    Route::middleware(['role_check'])->group(function () {
        Route::post('/music-list/store', [MusicListController::class, 'store']);
        Route::get('/music-list/{id}/edit', [MusicListController::class, 'edit']);
        Route::post('/music-list/{id}/update', [MusicListController::class, 'update']);
        Route::post('/music-list/{id}/delete', [MusicListController::class, 'delete']);
    });

});